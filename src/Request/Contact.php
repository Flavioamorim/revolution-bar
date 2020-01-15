<?php

namespace RDStation\Request;

use RDStation\Exception\IncorrectTypeException;
use Exception;

class Contact
{

    /** @var string $uuid */
    protected $uuid;

    /** @var string $name */
    protected $name;

    /** @var string email */
    protected $email;

    /** @var string $jobTitle */
    protected $jobTitle;

    /** @var string $bio */
    protected $bio;

    /** @var string $website */
    protected $website;

    /** @var string $linkedin */
    protected $linkedin;

    /** @var string $personalPhone */
    protected $personalPhone;

    /** @var string $city */
    protected $city;

    /** @var string $state */
    protected $state;

    /** @var string $country */
    protected $country;

    /** @var string $tags */
    protected $tags;

    /** @var string $extraEmails */
    protected $extraEmails;

    /**
     * @var array $extraFields
     */
    protected $extraFields = [];

    /**
     * @var string $indentifier
     */
    protected $identifier;

    /**
     * Contact constructor.
     * @param string $identifier email|uuid
     * @param array $extraFields
     * @throws IncorrectTypeException
     */
    public function __construct(string $identifier = ContactIdentifier::EMAIL, array $extraFields = [])
    {
        $this->extraFields = $extraFields;
        $this->setIdentifier($identifier);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @throws IncorrectTypeException
     */
    public function setEmail(string $email)
    {
        $this->emailValidate($email);
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getJobTitle()
    {
        return $this->jobTitle;
    }

    /**
     * @param string $jobTitle
     */
    public function setJobTitle(string $jobTitle)
    {
        $this->jobTitle = $jobTitle;
    }

    /**
     * @return string
     */
    public function getBio()
    {
        return $this->bio;
    }

    /**
     * @param string $bio
     */
    public function setBio(string $bio)
    {
        $this->bio = $bio;
    }

    /**
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * @param string $website
     * @throws IncorrectTypeException
     */
    public function setWebsite(string $website)
    {
        if (!filter_var($website, FILTER_VALIDATE_URL)) {
            throw new IncorrectTypeException("not a valid website");
        }

        $this->website = $website;
    }

    /**
     * @return string
     */
    public function getLinkedin()
    {
        return $this->linkedin;
    }

    /**
     * @param string $linkedin
     */
    public function setLinkedin(string $linkedin)
    {
        $this->linkedin = $linkedin;
    }

    /**
     * @return string
     */
    public function getPersonalPhone()
    {
        return $this->personalPhone;
    }

    /**
     * @param string $personalPhone
     */
    public function setPersonalPhone(string $personalPhone)
    {
        $this->personalPhone = $personalPhone;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity(string $city)
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $state
     */
    public function setState(string $state)
    {
        $this->state = $state;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry(string $country)
    {
        $this->country = $country;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param array $tags
     */
    public function setTags(array $tags)
    {
        $this->tags = $tags;
    }

    /**
     * @return array
     */
    public function getExtraEmails()
    {
        return $this->extraEmails;
    }

    /**
     * @param array $extraEmails
     */
    public function setExtraEmails(array $extraEmails)
    {
        array_walk($extraEmails, [static::class, "emailValidate"]);
        $this->extraEmails = $extraEmails;
    }

    /**
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @param string $uuid
     */
    public function setUuid(string $uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * @return array
     */
    public function getExtraFields()
    {
        return $this->extraFields;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @param string $identifier
     * @throws IncorrectTypeException
     */
    protected function setIdentifier(string $identifier)
    {
        $identifiersValid = ContactIdentifier::getIdentifiersValid();
        if (!in_array($identifier, $identifiersValid)) {
            $message = sprintf(
                "Sent identifier value is not valid. The valid values are: %s",
                implode(",", $identifiersValid)
            );
            throw new IncorrectTypeException($message);
        }

        $this->identifier = $identifier;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function toArray(): array
    {
        if (!$this->validateIfIdentifierWasInformed()) {
            throw new \Exception("The identifier value is empty.");
        }

        $defaultFields = [
            'uuid' => $this->getUuid(),
            'name' => $this->getName(),
            'email' => $this->getEmail(),
            'job_title' => $this->getJobTitle(),
            'bio' => $this->getBio(),
            'website' => $this->getWebsite(),
            'linkedin' => $this->getLinkedin(),
            'personal_phone' => $this->getPersonalPhone(),
            'city' => $this->getCity(),
            'state' => $this->getState(),
            'country' => $this->getCountry(),
            'tags' => $this->getTags(),
            'extra_emails' => $this->getExtraEmails()
        ];

        return array_merge($defaultFields, $this->extraFields);
    }

    /**
     * @param string $email
     * @throws IncorrectTypeException
     */
    protected function emailValidate(string $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new IncorrectTypeException("Not a valid email: " . $email);
        }
    }

    protected function validateIfIdentifierWasInformed(): bool
    {
        $validateRules = [
            ContactIdentifier::EMAIL => function () {
                return !empty($this->getEmail());
            },

            ContactIdentifier::UUID => function () {
                return !empty($this->getUuid());
            }
        ];

        return call_user_func($validateRules[$this->getIdentifier()]);
    }
}
