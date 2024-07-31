"use client";
import React, { useEffect, useState, useRef } from "react";
import axios from "axios";
import Modal from "react-bootstrap/Modal";
// import "parsleyjs";
import Link from "next/link";
import StateData from "@/static-data/StateData";
import { useParams } from "next/navigation";

export async function generateMetadata({ params }) {
  return {
    title: `Latest Government Tender Detail | Online eTender, Eprocurement, Bids | Govt ${process.env.NEXT_PUBLIC_SITE_NAME}`,
    description:
      "Looking for government tenders? Discover a reliable source to find all published tender details on tender 18. With Tender's 24/7 support system, get access to the latest government tenders, online tender information, and stay updated with local tender news. Explore government tenders today.",
  };
}
const AllTendersDetails = () => {
  // const location = useLocation();
  // const queryParams = new URLSearchParams(location.search);
  // const id = queryParams.get('id');

  const [data, setData] = useState([]);
  const [docs, setDocs] = useState([]);
  const [isLoggedIn, setIsLoggedIn] = useState(false);
  const [submitMessage, setSubmitMessage] = useState(null);
  const [errorMessage, setErrorMessage] = useState(null);
  const [show, setShow] = useState(false);
  const [formSubmitted, setFormSubmitted] = useState(false);
  const [formData, setFormData] = useState({
    username: "",
    company_name: "",
    email: "",
    mobile: "",
    state: "",
    description: "",
  });
  const formRef = useRef(null);
  const inputRef = useRef(null);
  let { id } = useParams();

  const handleClose = () => setShow(false);
  const handleShow = () => setShow(true);

  useEffect(() => {
    if (formRef.current) {
      // formRef.current.parsley();
    }
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
            } else {
              // window.location.href = '/tender18';
              setIsLoggedIn(false);
            }
          } catch (error) {
            console.error(error);
          }
        } else {
          // window.location.href = '/tender18';
          setIsLoggedIn(false);
          if (typeof window !== "undefined") {
            localStorage.removeItem("token");
            localStorage.removeItem("user_id");
          }
        }
      }
    };
    validateLogin();

    const fetchData = async () => {
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
            const response2 = await axios.post(
              `${process.env.NEXT_PUBLIC_API_BASEURL}` + "validate-login.php",
              user
            );
            if (response2.data.status == "success") {
              setIsLoggedIn(true);
              const user1 = {
                user_unique_id: user_id,
                ref_no: id[0],
                endpoint: "getTenderDetailsData",
              };
              const response1 = await axios.post(
                `${process.env.NEXT_PUBLIC_API_BASEURL}` + "tender-details.php",
                user1
              );
              setData(response1.data.data.tenders);
              setDocs(response1.data.data.tenders.documents);
            } else {
              setIsLoggedIn(false);
              let user1 = {};
              if (id[1] == null) {
                user1 = {
                  user_unique_id: user_id,
                  ref_no: id[0],
                  endpoint: "getTenderDetailsData",
                };
              } else {
                user1 = {
                  user_unique_id: id[1],
                  ref_no: id[0],
                  endpoint: "getTenderDetailsData",
                };
                setIsLoggedIn(true);
              }
              const response1 = await axios.post(
                `${process.env.NEXT_PUBLIC_API_BASEURL}` + "tender-details.php",
                user1
              );
              setData(response1.data.data.tenders);
              setDocs(response1.data.data.tenders.documents);
            }
          } catch (error) {
            console.error("Error fetching data:", error);
          }
        } else {
          setIsLoggedIn(false);
          let user1 = {};
          if (id[1] == null) {
            user1 = {
              user_unique_id: user_id,
              ref_no: id[0],
              endpoint: "getTenderDetailsData",
            };
          } else {
            user1 = {
              user_unique_id: id[1],
              ref_no: id[0],
              endpoint: "getTenderDetailsData",
            };
            setIsLoggedIn(true);
          }
          const response1 = await axios.post(
            `${process.env.NEXT_PUBLIC_API_BASEURL}` + "tender-details.php",
            user1
          );
          setData(response1.data.data.tenders);
          setDocs(response1.data.data.tenders.documents);
        }
      }
    };

    fetchData();
  }, []);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData((prevData) => ({ ...prevData, [name]: value }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();

    if (formData.username === "") {
      setErrorMessage("This Field is Required");
      return;
    }
    if (formData.email === "") {
      inputRef.current.classList.add("invalid");
      return;
    }
    if (formData.mobile === "") {
      inputRef.current.classList.add("invalid");
      return;
    }
    if (formData.state === "") {
      inputRef.current.classList.add("invalid");
      return;
    }

    const user = {
      name: `${formData.username}`,
      company_name: `${formData.company_name}`,
      email: `${formData.email}`,
      mobile: `${formData.mobile}`,
      state: `${formData.state}`,
      description: `${formData.description}`,
      endpoint: "saveTenderInquiryData",
      tender_id: id[0],
    };
    try {
      const alert = await axios.post(
        `${process.env.NEXT_PUBLIC_API_BASEURL}` + "tender-inquiry.php",
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
          description: "",
        });
      } else {
        setSubmitMessage("Mail Not Sent Successfully");
      }
    } catch (error) {
      setSubmitMessage("Mail Not Sent Successfully");
    }

    setFormSubmitted(true);
  };

  return (
    <>
      <div className="tenders-details-page-main">
        <div className="container-main">
          <div className="tenders-details-title">
            <h6>{data.title}</h6>
          </div>

          <div className="tender-details-page-flex">
            <div className="tenders-details-page-left">
              <div className="tender-details-btn">
                <Link href="">Tender Basic Details</Link>
              </div>

              <div className="tender-details-table">
                <table>
                  <tbody>
                    <tr>
                      <th>T18 Ref No:</th>
                      <td>{data.ref_no}</td>
                    </tr>
                    <tr>
                      <th>Tender ID:</th>
                      <td>{data.tender_id}</td>
                    </tr>
                    <tr>
                      <th>Tender Agency:</th>
                      <td>{data.agency}</td>
                    </tr>
                    <tr>
                      <th>City:</th>
                      <td>{data.city}</td>
                    </tr>
                    <tr>
                      <th>State:</th>
                      <td>{data.state}</td>
                    </tr>
                    <tr>
                      <th>Description :</th>
                      <td>{data.description}</td>
                    </tr>
                    {/* <tr>
                                        <th>Pin code:</th>
                                        <td>{data.pincode}</td>
                                    </tr> */}
                  </tbody>
                </table>
              </div>

              <div className="tender-details-btn">
                <Link href="">Key Values and Dates</Link>
              </div>

              <div className="tender-details-table">
                <table>
                  <tbody>
                    <tr>
                      <th>Tender Value:</th>
                      <td>{data.tender_value}</td>
                    </tr>
                    <tr>
                      <th>Tender EMD:</th>
                      <td>{data.tender_emd}</td>
                    </tr>
                    <tr>
                      <th>Tender Fee:</th>
                      <td>{data.tender_fee}</td>
                    </tr>
                    <tr>
                      <th>Published Date:</th>
                      <td>{data.publish_date}</td>
                    </tr>
                    <tr>
                      <th>Due Date:</th>
                      <td>{data.due_date}</td>
                    </tr>
                    <tr>
                      <th>Tender Opening Date:</th>
                      <td>{data.opening_date}</td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <div className="tender-details-btn">
                <Link href="">Tender Documents</Link>
              </div>

              <div className="tender-details-table">
                <table>
                  <tbody>
                    <tr className="td-download-btn">
                      <th>Tender Documents</th>
                      {isLoggedIn ? (
                        <td>
                          {docs?.map((downloaddata, index) => {
                            return (
                              <Link
                                href={downloaddata}
                                key={index}
                                target="_blank"
                                rel="noopener noreferrer"
                              >
                                Document {index + 1}
                              </Link>
                            );
                          })}
                        </td>
                      ) : (
                        <>
                          {/* <td>
                            <a
                              className="careers-btn"
                              data-bs-toggle="modal"
                              onClick={handleShow}
                            >
                              Download Document
                            </a>
                          </td> */}
                        </>
                      )}
                    </tr>
                  </tbody>
                </table>

                <div className="tender-modal">
                  <Modal
                    show={show}
                    onHide={handleClose}
                    className="tenders-modal"
                    centered
                  >
                    <Modal.Header closeButton></Modal.Header>
                    <Modal.Body className="tenders-modal-body">
                      <form
                        onSubmit={handleSubmit}
                        ref={formRef}
                        className="tender-inquiry"
                      >
                        <div className="why-us-form-flex">
                          <div className="why-us-form-block">
                            <input
                              type="text"
                              placeholder="Name"
                              name="username"
                              onChange={handleChange}
                              value={formData.username}
                              required
                            />
                          </div>
                          <div className="why-us-form-block">
                            <input
                              type="text"
                              placeholder="Company Name"
                              name="company_name"
                              value={formData.company_name}
                              onChange={handleChange}
                              required
                            />
                          </div>
                          <div className="why-us-form-block">
                            <input
                              type="email"
                              placeholder="Email"
                              name="email"
                              onChange={handleChange}
                              value={formData.email}
                              required
                            />
                          </div>
                          <div className="why-us-form-block">
                            <input
                              type="number"
                              placeholder="Mobile"
                              name="mobile"
                              onChange={handleChange}
                              value={formData.mobile}
                              required
                            />
                          </div>

                          <div className="why-us-form-block">
                            <select
                              name="state"
                              value={formData.state}
                              onChange={handleChange}
                              required
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
                    </Modal.Body>
                  </Modal>
                </div>
              </div>
            </div>
            <div className="tenders-details-page-right">
              <div className="tender-details-inq">
                <h6>Tender Inquiry</h6>
              </div>
              <div>
                  <form
                    onSubmit={handleSubmit}
                    ref={formRef}
                    className="tender-inquiry"
                  >
                    <div className="why-us-form-flex">
                      <div className="why-us-form-block">
                        <input
                          type="text"
                          placeholder="Name"
                          name="username"
                          onChange={handleChange}
                          value={formData.username}
                          required
                        />
                      </div>
                      <div className="why-us-form-block">
                        <input
                          type="text"
                          placeholder="Company Name"
                          name="company_name"
                          value={formData.company_name}
                          onChange={handleChange}
                          required
                        />
                      </div>
                      <div className="why-us-form-block">
                        <input
                          type="email"
                          placeholder="Email"
                          name="email"
                          onChange={handleChange}
                          value={formData.email}
                          required
                        />
                      </div>
                      <div className="why-us-form-block">
                        <input
                          type="number"
                          placeholder="Mobile"
                          name="mobile"
                          onChange={handleChange}
                          value={formData.mobile}
                          required
                        />
                      </div>

                      <div className="why-us-form-block">
                        <select
                          name="state"
                          value={formData.state}
                          onChange={handleChange}
                          required
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
                      <input type="submit" value="Submit" />
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
    </>
  );
};

export default AllTendersDetails;

