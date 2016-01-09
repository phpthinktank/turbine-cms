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

namespace Turbine\Config;


use Psr\Http\Message\ServerRequestInterface;

class HttpInitiator extends AbstractInitiator
{

    /**
     * @var ServerRequestInterface
     */
    private $request = null;

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
    public function execute()
    {
        $nodes = $this->getNodes();
        $requets = $this->getRequest();

        return $nodes;
    }

}