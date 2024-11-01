<?php

namespace Tussendoor\PropertyWebsite\Controllers;

use WP_REST_Request;
use Tussendoor\PropertyWebsite\App;
use Tussendoor\PropertyWebsite\Api\RestRoute;
use Tussendoor\PropertyWebsite\Module\Module;
use Tussendoor\PropertyWebsite\Helpers\Hasher;
use Tussendoor\PropertyWebsite\Helpers\RateLimiter;

class EndpointsController
{
    protected $endpoint = 'propertywebsite/v1';

    /**
     * Setup the API routes
     */
    public function setup()
    {
        if (App::bound('active_integration') === false) {
            return;
        }

        $this->setModule(App::get('active_integration'));
        
        RestRoute::get($this->endpoint, '/info/(?P<id>\d+)/(?P<hash>[\w-]+)', [$this, 'propertyInfo']);
        RestRoute::get($this->endpoint, '/exists/(?P<id>\d+)/(?P<hash>[\w-]+)', [$this, 'propertyExists']);
    }

    public function setModule(Module $module)
    {
        $this->module = $module;

        return $this;
    }

    /**
     * Get all the info from a property
     * @param  WP_REST_Request  $request The incomming request containing the property ID
     * @return WP_REST_Response
     */
    public function propertyInfo(WP_REST_Request $request)
    {
        if (!$this->isValidRequest($request)) {
            return $this->handleInvalidRequest();
        }

        $objectId = (int) $request->get_param('id');

        $objectData = $this->module->getData($objectId);
        if (empty($objectData)) {
            return $this->response(['Object not found.'], 404);
        }

        return $this->response($objectData);
    }

    /**
     * Check if a property exists for a given ID
     * @param  WP_REST_Request  $request The Request
     * @return WP_REST_Response
     */
    public function propertyExists(WP_REST_Request $request)
    {
        $objectId = (int) $request->get_param('id');

        if (empty($objectId)) {
            return new \WP_Error('404', 'No property ID given.', [
                'version' => App::get('plugin.version')
            ]);
        }
        
        $exists = $this->module->objectExists($objectId);

        if ($exists) {
            return $this->response(['status' => true]);
        }

        return new \WP_Error('404', 'Property not found.', [
            'post_id' => $objectId, 'version' => App::get('plugin.version')
        ]);
    }

    /**
     * Check that the request contains a valid access key and the user is not blocked.
     * @param  WP_REST_Request $request
     * @return bool
     */
    protected function isValidRequest($request)
    {
        return Hasher::accessKey() === $request->get_param('hash')
            && (new RateLimiter)->isBlocked($this->getIpAddress()) === false;
    }

    /**
     * Handle an invalid request. Addes the IP address to the RateLimiter. When a user
     * hits 3 invalid requests, it is blocked.
     * @return WP_REST_Response
     */
    protected function handleInvalidRequest()
    {
        (new RateLimiter)->add($this->getIpAddress());

        return $this->response('Invalid key supplied.', 403);
    }

    /**
     * Get the request IP address from the $_SERVER variable.
     * @return string
     */
    protected function getIpAddress()
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        } else {
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        }

        return $ipaddress === '::1' ? '127.0.0.1' : $ipaddress;
    }

    /**
     * Format a default response, including a HTTP code and plugin version
     * @param  Array            $data The original data to send
     * @param  int              $code A HTTP-like header code
     * @return WP_REST_Response Returns a WP_REST_Response
     */
    private function response($data, $code = 200)
    {
        return new \WP_REST_Response(
            [
                'code'      => intval($code),
                'response'  => $data,
                'version'   => App::get('plugin.version'),
            ],
            intval($code)
        );
    }
}
