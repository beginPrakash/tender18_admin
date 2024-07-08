"use client";
import React, { useState, useEffect, useRef } from "react";
import axios from "axios";
import HomeWhyUsData from "@/components/home/HomeWhyUsData";
import { Formik, ErrorMessage } from "formik";
import * as Yup from "yup";

const LoginForm = () => {
  const [isLoggedIn, setIsLoggedIn] = useState(false);
  console.log({ isLoggedIn });

  const handleSubmit = async (values) => {
    const user = {
      name: values?.username,
      password: values?.password,
      endpoint: "loginData",
    };
    try {
      const response = await axios.post(
        `${process.env.NEXT_PUBLIC_API_BASEURL}` + "login.php",
        user
      );
      if (response.data.status == "success") {
        if (typeof window !== "undefined") {
          localStorage.setItem("token", response.data.token);
          localStorage.setItem("user_id", response.data.user_id);
        }
        setIsLoggedIn(true);
        window.location.href = "/";
      } else {
        alert("Invalid username or password");
      }
    } catch (error) {
      console.error(error);
    }
  };

  const validateLogin = async () => {
    if (typeof window !== "undefined") {
      const token = localStorage.getItem("token");
      const user_id = localStorage.getItem("user_id");

      if (token && user_id) {
        const user = {
          user_unique_id: user_id,
          token: token,
          endpoint: "validateLoginData",
        };
        try {
          const response = await axios.post(
            `${process.env.NEXT_PUBLIC_API_BASEURL}` + "validate-login.php",
            user
          );
          if (response.data.status == "success") {
            setIsLoggedIn(true);
            if (typeof window !== "undefined") {
              window.location.href = "/";
            }
          } else {
            // window.location.href = '/';
            setIsLoggedIn(false);
            localStorage.removeItem("token");
            localStorage.removeItem("user_id");
          }
        } catch (error) {
          console.error(error);
        }
      } else {
        // window.location.href = '/';
        setIsLoggedIn(false);
        localStorage.removeItem("token");
        localStorage.removeItem("user_id");
      }
    }
  };
  useEffect(() => {
    validateLogin();
  }, []);

  return (
    <>
      <Formik
        initialValues={{ username: "", password: "" }}
        validationSchema={Yup.object().shape({
          username: Yup.string()
            .email("Invalid email")
            .required("Email is required"),
          password: Yup.string().required("Password is required"),
        })}
        onSubmit={handleSubmit}
      >
        {({ handleChange, values, handleSubmit, errors }) => {
          return (
            <div className="login-form-main">
              <div className="container-main">
                <div className="regsiter-form-widths">
                  <div className="registerform-top-flex">
                    <div className="register-form">
                      <div className="register-form-title">
                        <h2>Login</h2>
                      </div>
                      <div className="contact-page-right-inner">
                        <div className="contact-page-form-flex">
                          <div className="contact-page-block">
                            <label htmlFor="">Email</label>
                            <input
                              type="email"
                              placeholder="Email Address"
                              name="username"
                              className={errors?.username ? "error" : ""}
                              value={values?.username}
                              onChange={handleChange}
                            />
                            <p className="text-danger">
                              <ErrorMessage name="email" />
                            </p>
                          </div>

                          <div className="contact-page-block">
                            <label htmlFor="">Password</label>
                            <input
                              type="password"
                              placeholder="Password"
                              name="password"
                              className={errors?.password ? "error" : ""}
                              value={values?.password}
                              onChange={handleChange}
                            />
                            <p className="text-danger">
                              <ErrorMessage name="password" />
                            </p>
                          </div>
                        </div>

                        <div className="contact-page-submit">
                          <input
                            onClick={handleSubmit}
                            type="submit"
                            value="Sign In"
                          />
                        </div>
                      </div>
                    </div>

                    <div className="why-us-left">
                      <div className="why-us-left-title">
                        <h4>Why Tender18?</h4>
                        <p>
                          Once You Registered With Tender18 You Are Unlocking
                          All This Features. We Are Always Giving Smart Tender
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
    </>
  );
};

export default LoginForm;
