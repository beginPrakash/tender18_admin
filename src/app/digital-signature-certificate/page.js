// "use client";
// import React, { useEffect } from "react";
import TenderInformtaionServicesBanner from "@/components/tender-information-service/TenderInformtaionServicesBanner";
import DigitalSignatureInfo from "@/components/digital-signature-certificate/DigitalSignatureInfo";
import BannerInfo from "@/components/digital-signature-certificate/BannerInfo";
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
      <BannerInfo/>
      <DigitalSignatureInfo />
      <TenderInfoForm desc1="We Providing Digital Signature Certificate At Your Doorstep So No Need To Go Outside To Make Your Digital Identity. Also Not Required Any Types Of Physical Documents" />
    </>
  );
};

export default DigitalSignature;
