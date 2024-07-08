"use client";
import React, { useState, useRef, useEffect } from "react";
import axios from "axios";
import "parsleyjs";
import StateData from "@/static-data/StateData";

const TenderInfoForm = (props) => {
  const [submitMessage, setSubmitMessage] = useState(null);
  const [formData, setFormData] = useState({
    username: "",
    company_name: "",
    email: "",
    mobile: "",
    state: "",
  });
  const formRef = useRef(null);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData((prevData) => ({ ...prevData, [name]: value }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    const user = {
      name: `${formData.username}`,
      company_name: `${formData.company_name}`,
      email: `${formData.email}`,
      mobile: `${formData.mobile}`,
      state: `${formData.state}`,
      endpoint: "saveDailyAlertData",
    };
    try {
      const alert = await axios.post(
        `${process.env.NEXT_PUBLIC_API_BASEURL}` + "daily-alert.php",
        user
      );
      // console.log(alert.data.status);

      if (alert.data.status == " success") {
        setSubmitMessage("Mail Sent Successfully");

        setFormData({
          username: "",
          company_name: "",
          email: "",
          mobile: "",
          state: "",
        });
      } else {
        setSubmitMessage("Mail Not Sent Successfully");
      }
    } catch (error) {
      setSubmitMessage("Mail Not Sent Successfully");
    }
  };

  useEffect(() => {
    if (formRef.current) {
      // $(formRef.current).parsley();
    }
  }, []);

  return (
    <>
      <div className="tender-info-form-main">
        <div className="container-main">
          <div className="tenders-info-services-width">
            <div className="tender-info-form-flex">
              <div className="tender-info-form-left">
                <h2>Why Tender18</h2>
                <p>{props.desc1}</p>
                <p>{props.desc2}</p>
              </div>

              <div className="tender-info-form-right">
                <div className="why-us-right-main">
                  <div className="why-us-form-title">
                    <h4>Get A Free Quote</h4>
                  </div>
                  <form
                    onSubmit={handleSubmit}
                    ref={formRef}
                    data-parsley-validate
                  >
                    <div className="why-us-form-flex">
                      <div className="why-us-form-block">
                        <input
                          type="text"
                          placeholder="Name"
                          name="username"
                          onChange={handleChange}
                          value={formData.username}
                          data-parsley-required="true"
                        />
                      </div>
                      <div className="why-us-form-block">
                        <input
                          type="text"
                          placeholder="Company Name"
                          name="company_name"
                          value={formData.company_name}
                          onChange={handleChange}
                        />
                      </div>
                      <div className="why-us-form-block">
                        <input
                          type="email"
                          placeholder="Email"
                          name="email"
                          onChange={handleChange}
                          value={formData.email}
                          data-parsley-required="true"
                        />
                      </div>
                      <div className="why-us-form-block">
                        <input
                          type="number"
                          placeholder="Mobile"
                          name="mobile"
                          onChange={handleChange}
                          value={formData.mobile}
                          data-parsley-required="true"
                        />
                      </div>

                      <div className="why-us-form-block">
                        <select
                          name="state"
                          data-parsley-required="true"
                          value={formData.state}
                          onChange={handleChange}
                        >
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
                      <input type="submit" value="Register Now" />
                    </div>
                  </form>

                  {submitMessage && (
                    <p className="submit-text">{submitMessage}</p>
                  )}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </>
  );
};

export default TenderInfoForm;
