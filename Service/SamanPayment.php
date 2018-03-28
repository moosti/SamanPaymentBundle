<?php
/**
 * Created by PhpStorm.
 * User: mosi
 * Date: 3/21/18
 * Time: 1:56 AM
 */

namespace EricomGroup\SamanPaymentBundle\Service;


use Symfony\Component\DependencyInjection\ContainerInterface;

class SamanPayment
{
	const FORM_ACTION  = 'https://sep.shaparak.ir/Payment.aspx';
	const WEB_METHOD_URL = 'https://verify.sep.ir/Payments/ReferencePayment.asmx?wsdl';
	private $configs;
	private $merchantId;
	private $password;
	private $bankRate;
	private $isAutoSubmit = false;
	private $submitText = 'پرداخت';
	private $redirectUrl = '';
	private $totalAmount;
	private $paymentId;
	private $transactionId;
	public $errors;
	private $stateErrors = [
		'Canceled By User'     => 'تراکنش بوسیله خریدار کنسل شده',
		'Invalid Amount'       => 'مبلغ سند برگشتی  از مبلغ تراکنش اصلی بیشتر است',
		'Invalid Transaction'  => 'درخواست برگشت تراکنش رسیده است در حالی که تراکنش اصلی پیدا نمی شود',
		'Invalid Card Number'  => 'شماره کارت اشتباه است',
		'No Such Issuer'       => 'چنین صادر کننده کارتی وجود ندارد',
		'Expired Card Pick Up' => 'از تاریخ انقضای کارت گذشته است',
		'Incorrect PIN'        => 'رمز کارت اشتباه است pin',
		'No Sufficient Funds'  => 'موجودی به اندازه کافی در حساب شما نیست',
		'Issuer Down Slm'      => 'سیستم کارت بنک صادر کننده فعال نیست',
		'TME Error'            => 'خطا در شبکه بانکی',
		'Exceeds Withdrawal Amount Limit'      => 'مبلغ بیش از سقف برداشت است',
		'Transaction Cannot Be Completed'      => 'امکان سند خوردن وجود ندارد',
		'Allowable PIN Tries Exceeded Pick Up' => 'رمز کارت 3 مرتبه اشتباه وارد شده کارت شما غیر فعال اخواهد شد',
		'Response Received Too Late'           => 'تراکنش در شبکه بانکی تایم اوت خورده',
		'Suspected Fraud Pick Up'              => 'اشتباه وارد شده cvv2 ویا ExpDate فیلدهای'
	];

	private $verifyErrors = [
		'-1'  => 'خطای داخلی شبکه',
		'-2'  => 'سپرده ها برابر نیستند',
		'-3'  => 'ورودی ها حاوی کاراکترهای غیر مجاز میباشد',
		'-4'  => 'کلمه عبور یا کد فروشنده اشتباه است',
		'-5'  => 'خطای بانک اطلاعاتی',
		'-6'  => 'سند قبلا برگشت کامل خورده',
		'-7'  => 'رسید دیجیتالی تهی است',
		'-8'  => 'طول ورودی ها بیشتر از حد مجاز است',
		'-9'  => 'وجود کارکترهای غیر مجاز در مبلغ برگشتی',
		'-10' => 'رسید دیجیتالی حاوی کارکترهای غیر مجاز است',
		'-11' => 'طول ورودی ها کمتر از حد مجاز است',
		'-12' => 'مبلغ برگشتی منفی است',
		'-13' => 'مبلغ برگشتی برای برگشت جزیی بیش از مبلغ برگشت نخورده رسید دیجیتالی است',
		'-14' => 'چنین تراکنشی تعریف نشده است',
		'-15' => 'مبلغ برگشتی به صورت اعشاری داده شده',
		'-16' => 'خطای داخلی سیستم',
		'-17' => 'برگشت زدن تراکنشی که با کارت بانکی غیر از بانک سامان انجام شده',
		'-18' => 'فروشنده نامعتبر است ip address'
	];

	public function __construct(ContainerInterface $container)
	{
//		$configs = $container->getParameter('saman_payment.config');
		$this->bankRate = 1;
	}

	/**
	 * @param mixed $merchantId
	 * @return SamanPayment
	 */
	public function setMerchantId($merchantId)
	{
		$this->merchantId = $merchantId;
		return $this;
	}

	/**
	 * @param mixed $password
	 * @return SamanPayment
	 */
	public function setPassword($password)
	{
		$this->password = $password;
		return $this;
	}

	/**
	 * @param mixed $bankRate
	 * @return SamanPayment
	 */
	public function setBankRate($bankRate)
	{
		$this->bankRate = $bankRate;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getErrors()
	{
		return $this->errors;
	}

	/**
	 * @param bool $isAutoSubmit
	 * @return SamanPayment
	 */
	public function setIsAutoSubmit($isAutoSubmit)
	{
		$this->isAutoSubmit = $isAutoSubmit;
		return $this;
	}

	/**
	 * @param string $submitText
	 * @return SamanPayment
	 */
	public function setSubmitText($submitText)
	{
		$this->submitText = $submitText;
		return $this;
	}

	/**
	 * @param string $redirectUrl
	 * @return SamanPayment
	 */
	public function setRedirectUrl($redirectUrl)
	{
		$this->redirectUrl = $redirectUrl;
		return $this;
	}

	/**
	 * @param mixed $totalAmount
	 * @return SamanPayment
	 */
	public function setTotalAmount($totalAmount)
	{
		$this->totalAmount = $totalAmount;
		return $this;
	}

	/**
	 * @param mixed $paymentId
	 * @return SamanPayment
	 */
	public function setPaymentId($paymentId)
	{
		$this->paymentId = $paymentId;
		return $this;
	}

	/**
	 * @param mixed $transactionId
	 * @return SamanPayment
	 */
	public function setTransactionId($transactionId)
	{
		$this->transactionId = $transactionId;
		return $this;
	}


	/**
	 * @param string $transaction_id
	 * @param string $state
	 * @param string $amount
	 * @return bool
	 */
	public function receiverParams($transaction_id = '', $state = '', $amount = '')
	{
		if( ( empty($state) or empty($transaction_id) ) or $state != 'OK' ) {
			if(isset($this->errorState[$state])) {
				$this->errors = $this->stateErrors[$state];
			} else {
				$this->errors = "خطا در کد رهگیری";
			}
			return false;
		}

		$this->transactionId = $transaction_id;
		$this->totalAmount = $this->bankRate * $amount;
		//web method verify transaction return total amount or negative error code
		$verify = $this->verifyTransaction();

		if( $verify > 0 and $verify == $this->totalAmount) {
			return true;
		}
		else {
			$this->errors = "کاربر گرامی مشکلی در تایید پرداخت پیش آمده";
			if($verify<0){
				$this->errors = $this->verifyErrors[$verify];
			}
			return false;
		}
	}

	/**
	 * @return bool or total amount
	 */
	private function verifyTransaction()
	{
		if(empty($this->transactionId) or empty($this->merchantID) ) {
			return false;
		}
		$soapClient = new \SoapClient( SamanPayment::WEB_METHOD_URL );
		$result     = false;

		for($a=1; $a<3; ++$a) {
			$result  = $soapClient->verifyTransaction( $this->transactionId,$this->merchantId );
			if( $result != false ) {
				break;
			}
		}
		return $result;
	}

	public function getFormParam()
	{
		return [
			'merchantId' => $this->merchantId,
			'amount' => $this->totalAmount * $this->bankRate,
			'action' => SamanPayment::FORM_ACTION,
			'redirectUrl' => $this->redirectUrl,
			'resNum' => $this->paymentId,
			'autoSubmit' => $this->isAutoSubmit,
			'submitText' => $this->submitText
		];
	}
}