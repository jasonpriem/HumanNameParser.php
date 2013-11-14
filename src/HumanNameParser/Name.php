<?php

namespace HumanNameParser;

class Name {
    
    /**
     * @var string
     */
    private $leadingInitial;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $nicknames;

    /**
     * @var string
     */
    private $middleName;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var string
     */
    private $academicTitle;

    /**
     * @var string
     */
    private $suffix;

    /**
     * Gets the value of firstName.
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }
    
    /**
     * Sets the value of firstName.
     *
     * @param string $firstName the first name
     *
     * @return self
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Gets the value of nicknames.
     *
     * @return string
     */
    public function getNicknames()
    {
        return $this->nicknames;
    }
    
    /**
     * Sets the value of nicknames.
     *
     * @param string $nicknames the nicknames
     *
     * @return self
     */
    public function setNicknames($nicknames)
    {
        $this->nicknames = $nicknames;

        return $this;
    }

    /**
     * Gets the value of middleName.
     *
     * @return string
     */
    public function getMiddleName()
    {
        return $this->middleName;
    }
    
    /**
     * Sets the value of middleName.
     *
     * @param string $middleName the middle name
     *
     * @return self
     */
    public function setMiddleName($middleName)
    {
        $this->middleName = $middleName;

        return $this;
    }

    /**
     * Gets the value of lastName.
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }
    
    /**
     * Sets the value of lastName.
     *
     * @param string $lastName the last name
     *
     * @return self
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Gets the value of suffix.
     *
     * @return string
     */
    public function getSuffix()
    {
        return $this->suffix;
    }
    
    /**
     * Sets the value of suffix.
     *
     * @param string $suffix the suffix
     *
     * @return self
     */
    public function setSuffix($suffix)
    {
        $this->suffix = $suffix;

        return $this;
    }

    /**
     * Gets the value of leadingInitial.
     *
     * @return string
     */
    public function getLeadingInitial()
    {
        return $this->leadingInitial;
    }
    
    /**
     * Sets the value of leadingInitial.
     *
     * @param string $leadingInitial the leading initial
     *
     * @return self
     */
    public function setLeadingInitial($leadingInitial)
    {
        $this->leadingInitial = $leadingInitial;

        return $this;
    }

    /**
     * Gets the value of academicTitle.
     *
     * @return academicTitle
     */
    public function getAcademicTitle()
    {
        return $this->academicTitle;
    }
    
    /**
     * Sets the value of academicTitle.
     *
     * @param string $academicTitle the academic title
     *
     * @return self
     */
    public function setAcademicTitle($academicTitle)
    {
        $this->academicTitle = $academicTitle;

        return $this;
    }
}