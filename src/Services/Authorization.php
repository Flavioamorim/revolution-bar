<?php
namespace RDStation\Services;
use RDStation\Configuration\Routes;
use RDStation\Exception\ContentTypeInvalid;
use RDStation\Exception\RequestFailed;
use RDStation\Response\AuthorizationResponse;
use RDStation\Helpers\Request;
use RDStation\Helpers\BuildUrl;
class Authorization
{
    /** @var Request $request */
    protected $request;
    /** @var string $clientId */
    protected $clientId;
    /** @var string $callbackUrl */
    protected $callbackUrl;
    /** @var RDStationConfiguration $configuration */
    protected $configuration;
    /** @var string */
    private $code;
    /** @var string $clientSecret */
    private $clientSecret;
    /**
     * Authentication constructor.
     * @param string $clientId
     * @param string $clientSecret
     * @param string $code
     */
    public function __construct(string $clientId, string $clientSecret, string $code)
    {
        $this->request = new Request([]);
        $this->code    = $code;
        $this->clientSecret = $clientSecret;
        $this->clientId = $clientId;
    }
    /**
     * @return AuthorizationResponse
     * @throws \JsonException
     * @throws ContentTypeInvalid
     * @throws RequestFailed
     * @throws ContentTypeInvalid
     * @throws \RDStation\Exception\InvalidRouteException
     */
    public function getAccessToken()
    {
        $url = BuildUrl::getUrlByRoute(Routes::AUTHORIZATION);
        $parameters = [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'code' => $this->code
        ];
        return $this->generateAuthenticateResponse(
            $this->request->post(sprintf('%s', $url), $parameters)
        );
    }
    /**
     * @param array $response
     * @return AuthorizationResponse
     */
    private function generateAuthenticateResponse(array $response)
    {
        return new AuthorizationResponse(
            $response['access_token'],
            $response['refresh_token'],
            $response['expires_in']
        );
    }
}
