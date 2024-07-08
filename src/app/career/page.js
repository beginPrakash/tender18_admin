"use client";
import React, { useEffect } from "react";
import AboutBanner from "@/components/about-us/AboutBanner";
import CareersInfo from "@/components/career/CareersInfo";
import CareersListing from "@/components/career/CareersListing";

const Careers = () => {
  return (
    <>
      <AboutBanner title="Careers" />
      <CareersInfo />
      <CareersListing />
    </>
  );
};

export default Careers;
