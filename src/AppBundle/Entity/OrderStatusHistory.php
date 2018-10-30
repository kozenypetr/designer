<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;

use AppBundle\Entity\Order;
use AppBundle\Entity\OrderStatus;


/**
 * Product
 *
 * @ORM\Table(name="order_status_history")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OrderStatusHistoryRepository")
 */
class OrderStatusHistory
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
     * Stav objednavky
     * @ORM\ManyToOne(targetEntity="OrderStatus")
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id")
     */
    protected $status = null;

    /**
     * Stav objednavky
     * @ORM\ManyToOne(targetEntity="Order", inversedBy="history")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id")
     */
    protected $order = null;

    /**
     * @var boolean
     *
     * @ORM\Column(name="email", type="boolean", options={"default": false})
     */
    private $email = false;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text", nullable=true)
     */
    private $message;


    /**
     * @var string
     *
     * @ORM\Column(name="email_text", type="text", nullable=true)
     */
    private $emailText;

    /**
     * @var \DateTime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @var \DateTime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updated;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set email.
     *
     * @param bool $email
     *
     * @return OrderStatusHistory
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return bool
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set emailText.
     *
     * @param string|null $emailText
     *
     * @return OrderStatusHistory
     */
    public function setEmailText($emailText = null)
    {
        $this->emailText = $emailText;

        return $this;
    }

    /**
     * Get emailText.
     *
     * @return string|null
     */
    public function getEmailText()
    {
        return $this->emailText;
    }

    /**
     * Set created.
     *
     * @param \DateTime $created
     *
     * @return OrderStatusHistory
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created.
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated.
     *
     * @param \DateTime $updated
     *
     * @return OrderStatusHistory
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated.
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set status.
     *
     * @param \AppBundle\Entity\OrderStatus|null $status
     *
     * @return OrderStatusHistory
     */
    public function setStatus(\AppBundle\Entity\OrderStatus $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return \AppBundle\Entity\OrderStatus|null
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set order.
     *
     * @param \AppBundle\Entity\Order|null $order
     *
     * @return OrderStatusHistory
     */
    public function setOrder(\AppBundle\Entity\Order $order = null)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order.
     *
     * @return \AppBundle\Entity\Order|null
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set message.
     *
     * @param string|null $message
     *
     * @return OrderStatusHistory
     */
    public function setMessage($message = null)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message.
     *
     * @return string|null
     */
    public function getMessage()
    {
        return $this->message;
    }
}
