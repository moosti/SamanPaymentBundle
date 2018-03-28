<?php

namespace EricomGroup\SamanPaymentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\VarDumper\VarDumper;

class DefaultController extends Controller
{
	/**
	 * @Route("/")
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
    public function indexAction()
    {
    	$sPayment = $this->get('saman_payment')->setMerchantId('111111')->setPassword('passss');
    	$res = $sPayment->receiverParams('1111', 'ok', 25000);
    	VarDumper::dump($res);

        return $this->render('base.html.twig');
    }
}
