"use client";
import React, { useState, useEffect, useRef } from "react";
import Link from "next/link";
import axios from "axios";
import HomeWhyUsData from "@/components/home/HomeWhyUsData";
import { ErrorMessage, Formik } from "formik";
import * as Yup from "yup";

const RegisterForm = () => {
  const [submitMessage, setSubmitMessage] = useState(null);
  const initialValues = {
    username: "",
    company_name: "",
    email: "",
    mobile: "",
    state: "",
    description: "",
  };

  const onSubmit = async (values) => {
    const user = {
      name: `${values?.username}`,
      company_name: `${values?.company_name}`,
      email: `${values?.email}`,
      mobile: `${values?.mobile}`,
      state: `${values?.state}`,
      description: `${values?.description}`,
      endpoint: "saveRegistrationData",
    };
    try {
      const alert = await axios.post(
        `${process.env.NEXT_PUBLIC_API_BASEURL}` + "registration-form.php",
        user
      );
      if (alert.data.status == "success") {
        setSubmitMessage("Mail Sent Successfully");
        setFormData({
          username: "",
          company_name: "",
          email: "",
          mobile: "",
          state: "",
          description: "",
        });
      } else {
        setSubmitMessage("Mail Not Sent Successfully");
      }
    } catch (error) {
      setSubmitMessage("Mail Not Sent Successfully");
    }
  };

  return (
    <Formik
      initialValues={initialValues}
      validationSchema={Yup.object().shape({
        email: Yup.string()
          .email("Invalid email")
          .required("Email is required"),
        username: Yup.string().required("Name is required"),
        mobile: Yup.number().required("Mobile number is required"),
        state: Yup.string().required("State is required"),
      })}
      onSubmit={onSubmit}
    >
      {({ values, handleChange, errors, handleSubmit }) => {
        console.log("errors", errors);
        return (
          <div className="register-form-main">
            <div className="container-main">
              <div className="regsiter-form-widths">
                <div className="registerform-top-flex">
                  <div className="register-form">
                    <div className="register-form-title">
                      <h2>Register</h2>
                    </div>
                    <div className="contact-page-right-inner">
                      <div className="contact-page-form-flex">
                        <div className="contact-page-block">
                          <label htmlFor="">Name</label>
                          <input
                            type="text"
                            placeholder="Name"
                            name="username"
                            className={errors?.username ? "error" : ""}
                            onChange={handleChange}
                            value={values?.username}
                          />
                          <p className="text-danger">
                            <ErrorMessage name="username" />
                          </p>
                        </div>

                        <div className="contact-page-block">
                          <label htmlFor="">Company Name</label>
                          <input
                            type="text"
                            placeholder="Enter Your Company Name"
                            name="company_name"
                            onChange={handleChange}
                            className={errors?.company_name ? "error" : ""}
                            value={values.company_name}
                          />
                          <p className="text-danger">
                            <ErrorMessage name="company_name" />
                          </p>
                        </div>

                        <div className="contact-page-block">
                          <label htmlFor="">State</label>
                          <input
                            type="text"
                            placeholder="Enter Your State"
                            name="state"
                            className={errors?.state ? "error" : ""}
                            onChange={handleChange}
                            value={values?.state}
                          />
                          <p className="text-danger">
                            <ErrorMessage name="state" />
                          </p>
                        </div>

                        <div className="contact-page-block">
                          <label htmlFor="">Mobile</label>
                          <input
                            type="number"
                            placeholder="Mobile"
                            name="mobile"
                            className={errors?.mobile ? "error" : ""}
                            onChange={handleChange}
                            value={values?.mobile}
                          />
                          <p className="text-danger">
                            <ErrorMessage name="mobile" />
                          </p>
                        </div>

                        <div className="contact-page-block">
                          <label htmlFor="">Email</label>
                          <input
                            type="email"
                            placeholder="Email"
                            name="email"
                            className={errors?.email ? "error" : ""}
                            onChange={handleChange}
                            value={values?.email}
                          />
                          <p className="text-danger">
                            <ErrorMessage name="email" />
                          </p>
                        </div>

                        <div className="contact-page-block">
                          <label htmlFor="">Description</label>
                          <input
                            type="text"
                            placeholder="Description"
                            name="description"
                            className={errors?.description ? "error" : ""}
                            onChange={handleChange}
                            value={values?.description}
                          />
                          <p className="text-danger">
                            <ErrorMessage name="description" />
                          </p>
                        </div>
                      </div>

                      <div className="contact-page-submit">
                        <input
                          type="submit"
                          onClick={handleSubmit}
                          value="Register"
                        />
                      </div>

                      <div className="already-registerd">
                        <span>
                          Already Registered With Tender18?{" "}
                          <Link href="/sign-in"> Click Here To Log In</Link>
                        </span>
                      </div>

                      {submitMessage && (
                        <p className="submit-text">{submitMessage}</p>
                      )}
                    </div>
                  </div>

                  <div className="why-us-left">
                    <div className="why-us-left-title">
                      <h4>Why Tender18?</h4>
                      <p>
                        Once You Registered With Tender18 You Are Unlocking All
                        This Features. We Are Always Giving Smart Tender
                        Solution
                      </p>
                    </div>
                    {HomeWhyUsData?.map((whyusdata, index) => {
                      return (
                        <div className="why-us-block" key={index}>
                          <div className="why-us-block-inner">
                            <i className={whyusdata.icon}></i>
                            <p>{whyusdata.text}</p>
                          </div>
                        </div>
                      );
                    })}
                  </div>
                </div>
              </div>
            </div>
          </div>
        );
      }}
    </Formik>
  );
};

export default RegisterForm;
