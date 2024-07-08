"use client";
import React, { useEffect } from "react";
import TenderInformtaionServicesBanner from "./TenderInformtaionServicesBanner";
import TenderInfoForm from "./TenderInfoForm";
import ListTendersInfo from "../live-tenders/ListTendersInfo";
import FeaturesTendersInfo from "../tender-information-service/FeaturesTendersInfo";

const TenderInformtaionServices = () => {
  return (
    <>
      <TenderInformtaionServicesBanner
        img="/images/tender-information-banner.webp"
        alt="Tender Information Service"
        title="Tender Information Service"
        desc="First, Let's Understand A Term 'Tender'. Everyday There Are Lots Of Work Or Contract Published By Governing Agencies, Private Companies Public Sector Units Etc. They Are Publishing Every Work As A Form Of Tender. Tender18 Gathering All This Type Of Tender Information From All Open Sources With Tender Documents On Daily Basis. We Provide All This Information To Our Registered Clients Regularly Via E-Mail As Per Clients Specification And Requirements."
        innerimg="/images/tender-information-1.webp"
        innerImgAlt="Tender Information"
      />
      <ListTendersInfo />
      <FeaturesTendersInfo />
      <TenderInfoForm
        desc1="We Tender18 Always Understand Important Of Time And Money. Hence We Always Used Latest Technology Hardwares And Softwares That Give Us Exact Result As Every Our Customer Wants."
        desc2="With The Help Of This Technology We Gather Tender Information From Differrent Open Source Platforms And Sort It By Client Wise. As A Result Of This Technology We Provide All Sufficient Information At Right Time."
      />
    </>
  );
};

export default TenderInformtaionServices;
