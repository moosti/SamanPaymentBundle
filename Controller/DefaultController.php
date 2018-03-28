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
    	$payment = $this->getDoctrine()->getRepository('SamanPaymentBundle:Payment')->find('1');
    	$sPayment = $this->get('saman_payment');
    	$sPayment->setMerchantId('3232')
			->setIsAutoSubmit(false)
			->setPassword('2222')
			->setPaymentId($payment->getId())
			->setTotalAmount($payment->getAmount())
			->setRedirectUrl('http://some.url');
    	$res = $sPayment->receiverParams('1111', 'ok', 25000);
    	VarDumper::dump($res);

        return $this->render('@SamanPayment/Default/index.html.twig', [
        	'payment' => $sPayment->getFormParam()
		]);
    }
}
