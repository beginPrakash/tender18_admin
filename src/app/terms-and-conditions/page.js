"use client";
import React, { useEffect } from "react";
import TermsAndConditionsData from "@/components/terms-and-conditions/TermsAndConditionsData";

const TermsAndConditions = () => {
  return (
    <>
      <div className="terms-page-main">
        <div className="container-main">
          <div className="tenders-info-services-width terms-page-width">
            <div className="register-form-title terms-page-title">
              <h2>TERMS & CONDITIONS</h2>
              <p>
                These Terms And Conditions Govern Your Use Of This Tender18
                Infotech Website - Www.Tender18.Com. Please Read Through This
                Document Carefully. If You Do Not Agree With The Terms &
                Conditions, Do Not Use This Website. If You Do Use The Website,
                Your Conduct Indicates That You Agree To Be Bound By The Terms &
                Conditions.
              </p>
            </div>

            <div className="terms-page-main-block">
              {TermsAndConditionsData?.map((termsdata, index) => {
                return (
                  <div className="terms-page-block" key={index}>
                    <h6>{termsdata.title}</h6>
                    <p dangerouslySetInnerHTML={{ __html: termsdata.desc }}></p>
                  </div>
                );
              })}
            </div>
          </div>
        </div>
      </div>
    </>
  );
};

export default TermsAndConditions;
