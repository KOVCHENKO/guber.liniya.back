<?php

namespace App\src\Services\Claim\PIDStatus;


use App\src\Repositories\AddressRepository;
use App\src\Repositories\ClaimRepository;

class ChildClaim implements PIDClaimInterface
{
    protected $claimRepository;
    protected $addressRepository;

    /**
     * ClaimService constructor.
     * @param ClaimRepository $claimRepository
     * @param AddressRepository $addressRepository
     */
    public function __construct(
        ClaimRepository $claimRepository,
        AddressRepository $addressRepository
    )
    {
        $this->claimRepository = $claimRepository;
        $this->addressRepository = $addressRepository;
    }

    public function createBasedOnCall($data)
    {
        $organizationClaimBinding = $this->claimRepository->getOrganizationWhichResolvesClaim($data['pid']);

        $address = $this->addressRepository->create([
            'district' => $data['address']['district'],
            'location' => $data['address']['location']
        ]);

        $childClaim = $this->claimRepository->create([
            'firstname' =>  $data['firstName'],
            'lastname' =>  $data['lastName'],
            'middlename' =>  $data['middleName'],
            'name' => $data['call']['callId'],
            'description' =>  $data['description'],
            'link' => $data['call']['link'],
            'ats_status' => $data['call']['atsStatus'],
            'phone' => $data['phone'],
            'email' =>  $data['email'],
            'address_id' => $address['id'],
            'call_id' => $data['call']['id'],
            'problem_id' => $data['problem']['id'],
            'status' => 'created',
            'pid' => $data['pid'],
            'level' => $data['level'],
            'dispatch_status' => $data['dispatchStatus'],
            'close_status' => 'raw'
        ]);

        $this->claimRepository->assignClaimToResponsibleOrganization(
            $childClaim,
            $organizationClaimBinding->organization_id,
            'hide'
        );
    }
}