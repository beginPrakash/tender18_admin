"use client";
import { useEffect, useRef, useState } from "react";
import axios from "axios";
// import "parsleyjs";
import StateData from "@/static-data/StateData";

const HomeWhyUs = () => {
  const [data, setData] = useState([]);
  const [dataDetails, setDataDetails] = useState([]);
  const [aboutData, setAboutData] = useState([]);
  const [submitMessage, setSubmitMessage] = useState(null);
  const [formData, setFormData] = useState({
    username: "",
    company_name: "",
    email: "",
    mobile: "",
    state: "",
  });
  // const [isLoggedIn, setIsLoggedIn] = useState(false);
  const [loading, setLoading] = useState(true);

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

  const decodeHTML = (html) => {
    // const txt = document.createElement("textarea");
    // txt.innerHTML = html;
    // return txt.value;
  };

  useEffect(() => {
    const fetchData = async () => {
      try {
        const response = await axios.get(
          `${process.env.NEXT_PUBLIC_API_BASEURL}` +
            "homepage/why-section.php?endpoint=getWhyData"
        );
        const about = await axios.get(
          `${process.env.NEXT_PUBLIC_API_BASEURL}` +
            "homepage/about-tender18-section.php?endpoint=getAboutTender18Data"
        );
        setData(response.data.data.main);
        setAboutData(about.data.data.main);
        setDataDetails(Object.values(response.data.data.details));
      } catch (error) {
        console.error("Error fetching data:", error);
      } finally {
        setLoading(false);
      }
    };

    fetchData();

    // if (formRef.current) {
    //   formRef?.current?.parsley();
    // }
  }, []);

  // if (loading) {
  //   return <div className="loader">Loading....</div>;
  // }
  return (
    <>
      {loading ? (
        <div className="loader">
          <img src="/images/Iphone-spinner-2.gif" alt="" />
        </div>
      ) : (
        <div className="home-why-us-main">
          <div className="container-main">
            <div className="why-us-flex">
              <div className="why-us-left">
                <div className="why-us-left-title">
                  <h4>{data.title}</h4>
                  <p>{data.description}</p>
                </div>
                {dataDetails.map((whyusdata, index) => {
                  return (
                    <div className="why-us-block" key={index}>
                      <div className="why-us-block-inner">
                        <span
                          dangerouslySetInnerHTML={{
                            __html: decodeHTML(whyusdata.icon),
                          }}
                        ></span>
                        <p>{whyusdata.title}</p>
                      </div>
                    </div>
                  );
                })}
              </div>

              <div className="about-us-center">
                <div className="why-us-left-title">
                  <h4>{aboutData.title}</h4>
                  <p
                    dangerouslySetInnerHTML={{ __html: aboutData.description }}
                  ></p>
                </div>
              </div>

              <div className="why-us-right">
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
                          {StateData.map((state, index) => {
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
      )}
    </>
  );
};

export default HomeWhyUs;
