<?php
/*
*
* (c) Marco Bunge <marco_bunge@web.de>
*
* For the full copyright and license information, please view the LICENSE.txt
* file that was distributed with this source code.
*
* Date: 09.01.2016
* Time: 16:02
*/

namespace Turbine\Config\Http;


use League\Route\Http\Exception;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Turbine\Config\AbstractInitiator;

class Initiator extends AbstractInitiator
{

    /**
     * @var ServerRequestInterface
     */
    private $request = NULL;

    /**
     * @return ServerRequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param ServerRequestInterface $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

    /**
     * Load nodes
     * @return array
     */
    public function create()
    {
        $nodes = $this->getNodes();
        $request = $this->getRequest();
        $uri = $request->getUri();

        list($nodeId, $node) = $this->fetchNode($nodes, $uri);

        if (!isset($node['config'])) {
            $node['config'] = '/{{environment}}/{{node_id}}.json';
        }

        $configPath = str_replace([
            '{{environment}}',
            '{{node_id}}'
        ], [
            $this->getEnvironment(),
            $nodeId
        ], $node['config']);

        return $this->getFactory()->load($configPath, $this->getLocator());

    }

    /**
     * @param $nodes
     * @param $uri
     * @return mixed
     * @throws UnableToDetermineNodesException
     * @throws UnknownNodeTypeException
     */
    protected function fetchNode(array $nodes, UriInterface $uri)
    {
        foreach ($nodes as $nodeId => $node) {
            if (!isset($node['type']) || !isset($node['pattern'])) {
                continue;
            }
            if (strpos($node['type'], 'url') !== 0) {
                continue;
            }
            if (strpos($uri->getScheme(), 'http') !== 0) {
                continue;
            }

            $type = $node['type'];
            $pattern = $node['pattern'];
            $subject = NULL;

            //check if https is required
            if (isset($node['secure'])) {
                if ($node['secure'] && strpos($uri->getScheme(), 'https') !== 0) {
                    continue;
                }
            }

            //determine url part by type
            switch ($type) {
                case "url":
                    $subject = $uri;
                    break;
                case "url_path":
                    $subject = $uri->getPath();
                    break;

                case "url_host":
                    $subject = $uri->getHost();
                    break;

                case "url_query":
                    $subject = $uri->getQuery();
                    break;
                default:
                    throw new UnknownNodeTypeException($type);
            }

            if (preg_match_all('#' . $pattern . '#i', $subject)) {
                return [$nodeId, $node];
            }
        }

        throw new UnableToDetermineNodesException();
    }

}