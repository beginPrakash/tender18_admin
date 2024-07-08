"use client";
import React from "react";
import DigitalSignatureInfoData from "./DigitalSignatureInfoData";

const DigitalSignatureInfo = () => {
  return (
    <>
      <div className="features-tender-info-main">
        <div className="features-tender-info">
          <img src="/images/tender-information-3.webp" alt="Digital Signature Certificate" />

          <div className="features-lists">
            <div className="tenders-info-services-width">
              <div className="services-title">
                <h2>Features Of Digital Signature Certificate</h2>
              </div>

              <div className="features-list-flex">
                {DigitalSignatureInfoData.map((infodata, index) => {
                  return (
                    <div className="features-list-block" key={index}>
                      <div className="features-list-block-inner">
                        <div className="features-list-img">
                          <img src={infodata.icon} alt={infodata.alt} />
                        </div>
                        <div className="features-list-desc">
                          <p>{infodata.desc}</p>
                        </div>
                      </div>
                    </div>
                  );
                })}
              </div>
            </div>
          </div>
        </div>
      </div>

      <div className="who-will-get-info">
        <div className="container-main">
          <div className="tenders-info-services-width">
            <div className="who-will-get-flex">
              <div className="who-will-get-left">
                <h2>What You Will Get</h2>
              </div>

              <div className="who-will-get-right">
                <p>
                  We Deal In Class 3 Digital Signature Certificate For Any Types
                  Of Online Tender Bidding, GST And ITR Return
                </p>

                <h6>In Digital Signature Certificate You Will Get -</h6>

                <ul>
                  <li>
                    <i className="fa-solid fa-check"></i>Digital Signature
                    Certificate Token Preinstalled With Your Company Details In
                    Digital Form
                  </li>
                  <li>
                    <i className="fa-solid fa-check"></i>Not Required Of Any
                    Physical Documents
                  </li>
                  <li>
                    <i className="fa-solid fa-check"></i>Services At Your Door
                    Steps
                  </li>
                  <li>
                    <i className="fa-solid fa-check"></i>Document Preparation
                    For Vendor Registration
                  </li>
                  <li>
                    <i className="fa-solid fa-check"></i>Create Customer Profile
                    In Particular Department As Per Customer Credential(Vendor
                    Registration)
                  </li>
                  <li>
                    <i className="fa-solid fa-check"></i>Technical Support
                    Provided Upto Certificate Expiration Time Period
                  </li>
                  <li>
                    <i className="fa-solid fa-check"></i>Easy To Use And Secure
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div className="useful-service">
        <div className="container-main">
          <div className="tenders-info-services-width">
            <div className="useful-flex">
              <div className="useful-left">
                <h2>For Whom This Service Is Useful</h2>
              </div>

              <div className="useful-right">
                <p>
                  For All Who Wants To Participate In Etendering Process. Also
                  DSC Is Used For ITR Return And GST Return
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </>
  );
};

export default DigitalSignatureInfo;
