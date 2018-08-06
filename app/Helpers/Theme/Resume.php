<?php

namespace App\Helpers\Theme;

class Resume {

    /**
     * Avatar Images 
     *
     * @var string
     */
    protected $avatarImages;

    /**
     * Cover Images 
     *
     * @var string
     */
    protected $coverImages;

    /**
     * Email
     *
     * @var string
     */
    protected $email;

    /**
     * First Name 
     *
     * @var string
     */
    protected $firstName;

    /**
     * Last Name 
     *
     * @var string
     */
    protected $lastName;

    /**
     * Dob
     *
     * @var string
     */
    protected $dob;

    /**
     * Gender
     *
     * @var \Illuminate\Support\Collection
     */
    protected $gender;

    /**
     * Marital Status 
     *
     * @var \Illuminate\Support\Collection
     */
    protected $maritalStatus;

    /**
     * About Me 
     *
     * @var string
     */
    protected $aboutMe;

    /**
     * Street Name 
     *
     * @var string
     */
    protected $streetName;

    /**
     * Country
     *
     * @var \Illuminate\Support\Collection
     */
    protected $country;

    /**
     * City
     *
     * @var \Illuminate\Support\Collection
     */
    protected $city;

    /**
     * District
     *
     * @var \Illuminate\Support\Collection
     */
    protected $district;

    /**
     * Ward
     *
     * @var \Illuminate\Support\Collection
     */
    protected $ward;

    /**
     * Phone Number 
     *
     * @var string
     */
    protected $phoneNumber;

    /**
     * Website
     *
     * @var string
     */
    protected $website;

    /**
     * Social Networks 
     *
     * @var string
     */
    protected $socialNetworks;

    /**
     * Skills
     *
     * @var \Illuminate\Support\Collection
     */
    protected $skills;

    /**
     * Employments
     *
     * @var \Illuminate\Support\Collection
     */
    protected $employments;

    /**
     * Educations
     *
     * @var \Illuminate\Support\Collection
     */
    protected $educations;
    
    /**
     * Expected job
     *
     * @var string
     */
    protected $expectedJob;
    
    /**
     * Hobbies
     *
     * @var string
     */
    protected $hobbies;

    /**
     * Get avatar images 
     *
     * @return string
     */
    public function getAvatarImages() {
        return $this->avatarImages;
    }

    /**
     * Set avatar images 
     *
     * @param string $avatarImages
     */
    public function setAvatarImages($avatarImages) {
        $this->avatarImages = $avatarImages;
    }

    /**
     * Get cover images 
     *
     * @return string
     */
    public function getCoverImages() {
        return $this->coverImages;
    }

    /**
     * Set cover images 
     *
     * @param string $coverImages
     */
    public function setCoverImages($coverImages) {
        $this->coverImages = $coverImages;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email) {
        $this->email = $email;
    }

    /**
     * Get first name 
     *
     * @return string
     */
    public function getFirstName() {
        return $this->firstName;
    }

    /**
     * Set first name 
     *
     * @param string $firstName
     */
    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    /**
     * Get last name 
     *
     * @return string
     */
    public function getLastName() {
        return $this->lastName;
    }

    /**
     * Set last name 
     *
     * @param string $lastName
     */
    public function setLastName($lastName) {
        $this->lastName = $lastName;
    }

    /**
     * Get dob
     *
     * @return string
     */
    public function getDob() {
        return $this->dob;
    }

    /**
     * Set dob
     *
     * @param string $dob
     */
    public function setDob($dob) {
        $this->dob = $dob;
    }

    /**
     * Get gender
     *
     * @return \Illuminate\Support\Collection
     */
    public function getGender() {
        return $this->gender;
    }

    /**
     * Set gender
     *
     * @param \Illuminate\Support\Collection $gender
     */
    public function setGender($gender) {
        $this->gender = $gender;
    }

    /**
     * Get marital status 
     *
     * @return \Illuminate\Support\Collection
     */
    public function getMaritalStatus() {
        return $this->maritalStatus;
    }

    /**
     * Set marital status 
     *
     * @param \Illuminate\Support\Collection $maritalStatus
     */
    public function setMaritalStatus($maritalStatus) {
        $this->maritalStatus = $maritalStatus;
    }

    /**
     * Get about me 
     *
     * @return string
     */
    public function getAboutMe() {
        return $this->aboutMe;
    }

    /**
     * Set about me 
     *
     * @param string $aboutMe
     */
    public function setAboutMe($aboutMe) {
        $this->aboutMe = $aboutMe;
    }

    /**
     * Get street name 
     *
     * @return string
     */
    public function getStreetName() {
        return $this->streetName;
    }

    /**
     * Set street name 
     *
     * @param string $streetName
     */
    public function setStreetName($streetName) {
        $this->streetName = $streetName;
    }

    /**
     * Get country
     *
     * @return \Illuminate\Support\Collection
     */
    public function getCountry() {
        return $this->country;
    }

    /**
     * Set country
     *
     * @param \Illuminate\Support\Collection $country
     */
    public function setCountry($country) {
        $this->country = $country;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity() {
        return $this->city;
    }

    /**
     * Set city
     *
     * @param \Illuminate\Support\Collection $city
     */
    public function setCity($city) {
        $this->city = $city;
    }

    /**
     * Get district
     *
     * @return \Illuminate\Support\Collection
     */
    public function getDistrict() {
        return $this->district;
    }

    /**
     * Set district
     *
     * @param \Illuminate\Support\Collection $district
     */
    public function setDistrict($district) {
        $this->district = $district;
    }

    /**
     * Get ward
     *
     * @return \Illuminate\Support\Collection
     */
    public function getWard() {
        return $this->ward;
    }

    /**
     * Set ward
     *
     * @param \Illuminate\Support\Collection $ward
     */
    public function setWard($ward) {
        $this->ward = $ward;
    }

    /**
     * Get phone number 
     *
     * @return string
     */
    public function getPhoneNumber() {
        return $this->phoneNumber;
    }

    /**
     * Set phone number 
     *
     * @param string $phoneNumber
     */
    public function setPhoneNumber($phoneNumber) {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * Get website
     *
     * @return string
     */
    public function getWebsite() {
        return $this->website;
    }

    /**
     * Set website
     *
     * @param string $website
     */
    public function setWebsite($website) {
        $this->website = $website;
    }

    /**
     * Get social networks 
     *
     * @return string
     */
    public function getSocialNetworks() {
        return $this->socialNetworks;
    }

    /**
     * Set social networks 
     *
     * @param string $socialNetworks
     */
    public function setSocialNetworks($socialNetworks) {
        $this->socialNetworks = $socialNetworks;
    }

    /**
     * Get skills
     *
     * @return \Illuminate\Support\Collection
     */
    public function getSkills() {
        return $this->skills;
    }

    /**
     * Set skills
     *
     * @param \Illuminate\Support\Collection $skills
     */
    public function setSkills($skills) {
        $this->skills = $skills;
    }

    /**
     * Get employments
     *
     * @return \Illuminate\Support\Collection
     */
    public function getEmployments() {
        return $this->employments;
    }

    /**
     * Set employments
     *
     * @param \Illuminate\Support\Collection $employments
     */
    public function setEmployments($employments) {
        $this->employments = $employments;
    }

    /**
     * Get educations
     *
     * @return \Illuminate\Support\Collection
     */
    public function getEducations() {
        return $this->educations;
    }

    /**
     * Set educations
     *
     * @param \Illuminate\Support\Collection $educations
     */
    public function setEducations($educations) {
        $this->educations = $educations;
    }
    
    /**
     * Get expected job
     *
     * @return string
     */
    public function getExpectedJob() {
        return $this->expectedJob;
    }

    /**
     * Set expected job
     *
     * @param string expectedJob
     */
    public function setExpectedJob($expectedJob) {
        $this->expectedJob = $expectedJob;
    }
    
    /**
     * Get hobbies
     *
     * @return string
     */
    public function getHobbies() {
        return $this->hobbies;
    }

    /**
     * Set hobbies
     *
     * @param string hobbies
     */
    public function setHobbies($hobbies) {
        $this->hobbies = $hobbies;
    }

}
