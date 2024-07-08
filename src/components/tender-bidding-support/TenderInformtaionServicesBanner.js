"use client";
import React from "react";

const TenderInformtaionServicesBanner = (props) => {
  return (
    <>
      <div className="tenders-information-banner">
        <div className="tenders-information-banner-img">
          <img src={props.img} alt={props.alt} />
        </div>
      </div>

      <div className="tenders-info-services-main">
        <div className="container-main">
          <div className="tenders-info-services-width">
            <div className="tenders-info-services-title services-title text-center">
              <h2>{props.title}</h2>
            </div>
            <div className="tenders-info-services-flex">
              <div className="tenders-info-services-left">
                <p>{props.desc}</p>
              </div>

              <div className="tenders-info-services-right">
                <img src={props.innerimg} alt="Tender Information" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </>
  );
};

export default TenderInformtaionServicesBanner;
