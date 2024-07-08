"use client";
import React from "react";
import FeaturesTendersInfoData from "./FeaturesTendersInfoData";

const FeaturesTendersInfo = () => {
  return (
    <>
      <div className="features-tender-info-main">
        <div className="features-tender-info">
          <img src="/images/tender-information-3.webp" alt="Digital Signature Certificate" />

          <div className="features-lists">
            <div className="tenders-info-services-width">
              <div className="services-title">
                <h2>Features Of Tender Information Service</h2>
              </div>

              <div className="features-list-flex">
                {FeaturesTendersInfoData.map((infodata, index) => {
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
                  Tender18 Always Worry About Its Clients That's Why We Are
                  Working Hard To Collect All Tender Information From Different
                  Resources And Gather It At A One Platform. Now We Are All
                  Tender Information As Per Clients Specification And
                  Requirements. At The End Of The Process Clients Will Get Below
                  Benifits From Our End.
                </p>

                <h6>Activated (Subscribed) Members Will Get -</h6>

                <ul>
                  <li>
                    <i className="fa-solid fa-bell"></i>Daily Email Alert
                  </li>
                  <li>
                    <i className="fa-solid fa-desktop"></i>Personal Log In Panel
                  </li>
                  <li>
                    <i className="fa-solid fa-phone"></i>Quick Tender Submission
                    Support
                  </li>
                  <li>
                    <i className="fa-solid fa-folder"></i>Complete Tender
                    Documents
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
                  We Are One Of India's Leading Tender Information Provider
                  Company Which Only Aim Is To Provide Best Tender Information
                  To All Our Clients. According This We Are Working Hard To
                  Provide Right Tender Information To The Right Client To Right
                  Time.
                </p>
                <p>
                  We Are Providing All Types Of Tende Information From
                  Government Agencies, Private Companies, Public Sector Units
                  And All Other Departments. Also We Provide Information Of
                  Minimum To Highest Value Of Tenders. So Our Tender Information
                  Is Useful For Any Type Of Small Company To Midium And Large
                  Companies.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </>
  );
};

export default FeaturesTendersInfo;
