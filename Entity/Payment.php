<?php

namespace EricomGroup\SamanPaymentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Payment
 *
 * @ORM\Table(name="payment")
 * @ORM\Entity(repositoryClass="EricomGroup\SamanPaymentBundle\Repository\PaymentRepository")
 */
class Payment
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="transaction_id", type="string", nullable=true)
     */
    private $transactionId;

    /**
     * @var float
     *
     * @ORM\Column(name="amount", type="float", nullable=true)
     */
    private $amount;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="payed_at", type="datetime", nullable=true)
     */
    private $payedAt;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set transactionId
     *
     * @param integer $transactionId
     *
     * @return Payment
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    /**
     * Get transactionId
     *
     * @return int
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * Set amount
     *
     * @param float $amount
     *
     * @return Payment
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set payedAt
     *
     * @param \DateTime $payedAt
     *
     * @return Payment
     */
    public function setPayedAt($payedAt)
    {
        $this->payedAt = $payedAt;

        return $this;
    }

    /**
     * Get payedAt
     *
     * @return \DateTime
     */
    public function getPayedAt()
    {
        return $this->payedAt;
    }
}

