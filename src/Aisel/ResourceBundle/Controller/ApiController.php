<?php

/*
 * This file is part of the Aisel package.
 *
 * (c) Ivan Proskuryakov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aisel\ResourceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use JMS\Serializer\SerializationContext;

/**
 * Class ApiController
 *
 * @author Ivan Proskuryakov <volgodark@gmail.com>
 */
class ApiController extends Controller
{
    /**
     * @var string
     */
    protected $model;

    /**
     * @return DocumentManager
     */
    protected function getDocumentManager()
    {
        $dm = $this->get('doctrine.odm.mongodb.document_manager');

        return $dm;
    }

    /**
     * filterMaxDepth
     *
     * @param mixed $entity
     *
     * @return mixed $entity
     */
    protected function filterMaxDepth($entity)
    {
        $entity = $this
            ->container
            ->get('jms_serializer')
            ->serialize(
                $entity,
                'json',
                SerializationContext::create()->enableMaxDepthChecks()
            );

        return json_decode($entity, true);
    }

    /**
     *
     * getByURLAction
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function getURLAction(Request $request)
    {
        $entity = $this->getEntityFromRequest($request);

        return $this->filterMaxDepth($entity);
    }

    /**
     * @param Request $request
     *
     * @return null|mixed $entity
     */
    protected function getEntityFromRequest(Request $request)
    {
        $configuration = new ParamConverter(array(
            'class' => $this->model
        ));

        $entity = $this->get('api_param_converter')->execute($request, $configuration);

        return $entity;
    }

    /**
     * @param $entity
     * @param null $id
     *
     * @return Response
     */
    protected function processEntity($entity, $id = null)
    {
        if ($id) {
            $statusCode = 204;
        } else {
            $statusCode = (null === $entity->getId()) ? 201 : 204;
        }

        $this->getDocumentManager()->persist($entity);
        $this->getDocumentManager()->flush();

        $response = new Response();
        $response->setStatusCode($statusCode);

        // set the `Location` header only when creating new resources
        if (201 === $statusCode) {
            $route = str_replace(
                "_post",
                '_get',
                $this->container->get('request')->get('_route')
            );
            $response->headers->set(
                'Location',
                $this->generateUrl(
                    $route,
                    array('id' => $entity->getId()),
                    true // absolute
                )
            );
        }

        return $response;
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function putAction(Request $request)
    {
        $entity = $this->getEntityFromRequest($request);
        $this->processEntity($entity);
    }

    /**
     * postAction
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function postAction(Request $request)
    {
        $entity = $this->getEntityFromRequest($request);

        return $this->processEntity($entity);
    }

    /**
     * getAction
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function getAction(Request $request)
    {
        $entity = $this->getEntityFromRequest($request);

        return $this->filterMaxDepth($entity);
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function getByURLAction(Request $request)
    {
        $entity = $this->getEntityFromRequest($request);

        return $this->filterMaxDepth($entity);
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function deleteAction(Request $request)
    {
        $document = $this->getEntityFromRequest($request);
        $dm = $this->getDocumentManager();

        $dm->remove($document);
        $dm->flush();
        $dm->clear();
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function getCollectionAction(Request $request)
    {
        $params = array(
            'locale' => $request->get('locale'),
            'current' => $request->get('current'),
            'limit' => $request->get('limit'),
            'node' => $request->get('node'),
            'order' => $request->get('order'),
            'orderBy' => $request->get('orderBy'),
            'filter' => $request->get('filter'),
            'scope' => $this->getRequestScope($request)
        );

        /**
         * @var $repo \Aisel\ResourceBundle\Repository\CollectionRepository
         */
        $repo = $this
            ->getDocumentManager()
            ->getRepository($this->model);
        $total = $repo->getTotalFromRequest($params);
        $collection = array_values($repo->getCollectionFromRequest($params));

        return array(
            'total' => $total,
            'collection' => $collection
        );
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function getTreeAction(Request $request)
    {
        $params = array(
            'locale' => $request->get('locale'),
            'scope' => $this->getRequestScope($request)
        );

        /**
         * @var $repo \Aisel\ResourceBundle\Repository\CollectionRepository
         */
        $repo = $this
            ->getDocumentManager()
            ->getRepository($this->model);
        $collection = array_values($repo->getNodesAsTree($params));

        return $collection;
    }

    /**
     * Get request scope
     *
     * @param Request $request
     * @return string $status
     */
    protected function getRequestScope(Request $request)
    {
        $scope = 'backend';

        $urlFrontend = $this
            ->container
            ->getParameter('frontend_api');

        if (strpos($request->getUri(), $urlFrontend)) {
            $scope = 'frontend';
        }

        return $scope;
    }

}
