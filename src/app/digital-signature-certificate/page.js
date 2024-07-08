// "use client";
// import React, { useEffect } from "react";
import TenderInformtaionServicesBanner from "@/components/tender-information-service/TenderInformtaionServicesBanner";
import DigitalSignatureInfo from "@/components/digital-signature-certificate/DigitalSignatureInfo";
import TenderInfoForm from "@/components/tender-info/TenderInfoForm";

export async function generateMetadata({ params }) {
  return {
    title: `Online Digital Signature Certificate | Class III DSC Apply for Tenders - ${process.env.NEXT_PUBLIC_SITE_NAME}`,
    description:
      "Need a Class III DSC? Apply for online digital signature certificate and bid for tenders hassle free with tender18. Get secure, government approved authentication for all your tender submissions",
  };
}

const DigitalSignature = () => {
  return (
    <>
      <TenderInformtaionServicesBanner
        title="Digital Signature Certificate - Class III DSC Apply for Tenders"
        desc="We Deal In Digital Signature Certificate(DSC) Also. We Are Provide Class 3 Digital Signature Certificate Which Is Used For ITR Filling, GST Return, For Participating Of Etenders In Any Etender Departments. Also We Provide DSC For Proprietorship, Partnertship Company, Private Limited And Public Limited Companies. We Also Provide Technical Support For Attatch DSC Upto Validity Time Period. Your Provided Data Is Safe And DSC Is Easy To Use Any Government Departments"
        innerimg="/images/dsc-img1.webp"
        innerImgAlt="Digital Signature Certificate Services"
      />
      <DigitalSignatureInfo />
      <TenderInfoForm desc1="We Providing Digital Signature Certificate At Your Doorstep So No Need To Go Outside To Make Your Digital Identity. Also Not Required Any Types Of Physical Documents" />
    </>
  );
};

export default DigitalSignature;
