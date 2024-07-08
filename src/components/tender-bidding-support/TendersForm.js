"use client";
import StateData from "@/static-data/StateData";
import React from "react";

const TendersForm = () => {
  return (
    <>
      <div className="tenders-form-main">
        <div className="why-us-right-main">
          <div className="why-us-form-title">
            <h4>Get In Touch</h4>
          </div>

          <form action="">
            <div className="why-us-form-flex">
              <div className="why-us-form-block">
                <input type="text" placeholder="Name" />
              </div>
              <div className="why-us-form-block">
                <input type="text" placeholder="Company Name" />
              </div>
              <div className="why-us-form-block">
                <input type="email" placeholder="Email" />
              </div>
              <div className="why-us-form-block">
                <input type="number" placeholder="Mobile" />
              </div>

              <div className="why-us-form-block">
                <select name="">
                  <option value="">Services</option>
                  <option value="">Tender Information</option>
                  <option value="">Tender Bidding Support</option>
                  <option value="">GeM Services</option>
                  <option value="">Digital Signature Certificate (DSC)</option>
                </select>
              </div>

              <div className="why-us-form-block">
                <select name="">
                  {StateData?.map((state, index) => {
                    return (
                      <option value={state.value} key={index}>
                        {state.data}
                      </option>
                    );
                  })}
                </select>
              </div>
            </div>

            <div className="why-us-form-submit">
              <input type="submit" value="Submit" />
            </div>
          </form>
        </div>
      </div>
    </>
  );
};

export default TendersForm;
