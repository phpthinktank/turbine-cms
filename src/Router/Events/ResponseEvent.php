<?php
/**
 *
 * (c) Marco Bunge <marco_bunge@web.de>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 *
 * Date: 13.01.2016
 * Time: 16:03
 *
 */

namespace Turbine\Application\Events;


use Psr\Http\Message\ResponseInterface;

class ResponseEvent
{
    /**
     * @var ResponseInterface|string
     */
    private $response;

    /**
     * @return ResponseInterface|string
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param ResponseInterface|string $response
     * @return $this
     */
    public function setResponse($response)
    {
        $this->response = $response;

        return $this;
    }

    public function getName()
    {
        return 'router.dispatch.response';
    }

}