"use client";
import React, { useEffect, useState, useRef } from "react";
import axios from "axios";
import { useSearchParams } from "next/navigation";
import Link from "next/link";
import Modal from "react-bootstrap/Modal";
import StateData from "@/static-data/StateData";

const AfterLoginLiveTenders = () => {
  const [data, setData] = useState([]);
  const [link, setLink] = useState([]);
  const [user, setUser] = useState([]);
  const [loading, setLoading] = useState(false);
  const [isLoggedIn, setIsLoggedIn] = useState(false);
  const [submitMessage, setSubmitMessage] = useState(null);
  const [errorMessage, setErrorMessage] = useState(null);
  const [search, setSearch] = useState("");
  const [show, setShow] = useState(false);
  const [cshow, setCShow] = useState(false);
  const [formSubmitted, setFormSubmitted] = useState(false);
  const [formData, setFormData] = useState({
    search: "",
  });
  const [fformData, setFFormData] = useState({
    search: "",
    username: "",
    email: "",
    mobile: "",
    description: "",
  });
  const [cformData, setCFormData] = useState({
    search: "",
    username: "",
    email: "",
    mobile: "",
    description: "",
  });
  const handleClose = () => setShow(false);
  const handleShow = () => setShow(true);
  const handleCClose = () => setCShow(false);
  const handleCShow = () => setCShow(true);
  const formRef = useRef(null);
  const inputRef = useRef(null);
  // const [searchData, setSearchData] = useState(null);

  const searchParams = useSearchParams();
  const id = searchParams.get("id") || "";

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData((prevData) => ({ ...prevData, [name]: value }));
    setFFormData((prevData) => ({ ...prevData, [name]: value }));
  };

  const handleCChange = (e) => {
    const { name, value } = e.target;
    setCFormData((prevData) => ({ ...prevData, [name]: value }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();

    if (fformData.username === "") {
      setErrorMessage("This Field is Required");
      return;
    }
    if (fformData.email === "") {
      inputRef.current.classList.add("invalid");
      return;
    }
    if (fformData.mobile === "") {
      inputRef.current.classList.add("invalid");
      return;
    }

    const user = {
      name: `${fformData.username}`,
      email: `${fformData.email}`,
      mobile: `${fformData.mobile}`,
      description: `${fformData.description}`,
      endpoint: "saveFeedbackData",
      tender_id: id,
    };
    try {
      const alert = await axios.post(
        `${process.env.NEXT_PUBLIC_API_BASEURL}` + "create_feedback_inquiry.php",
        user
      );
      // console.log(alert.data.status);

      if (alert.data.status == " success") {
        setSubmitMessage("Feedback saved Successfully");

        setFFormData({
          username: "",
          email: "",
          mobile: "",
          description: "",
        });
      } else {
        setSubmitMessage("Feedback not saved Successfully");
      }
    } catch (error) {
      setSubmitMessage("Feedback not saved Successfully");
    }

    setFormSubmitted(true);
  };

  const handleCSubmit = async (e) => {
    e.preventDefault();

    if (cformData.username === "") {
      setErrorMessage("This Field is Required");
      return;
    }
    if (cformData.email === "") {
      inputRef.current.classList.add("invalid");
      return;
    }
    if (cformData.mobile === "") {
      inputRef.current.classList.add("invalid");
      return;
    }

    const user = {
      name: `${cformData.username}`,
      email: `${cformData.email}`,
      mobile: `${cformData.mobile}`,
      description: `${cformData.description}`,
      endpoint: "saveComplainData",
      tender_id: id,
    };
    try {
      const alert = await axios.post(
        `${process.env.NEXT_PUBLIC_API_BASEURL}` + "create_complain_inquiry.php",
        user
      );
      // console.log(alert.data.status);

      if (alert.data.status == " success") {
        setSubmitMessage("Complain   saved Successfully");

        setCFormData({
          username: "",
          email: "",
          mobile: "",
          description: "",
        });
      } else {
        setSubmitMessage("Complain   not saved Successfully");
      }
    } catch (error) {
      setSubmitMessage("Complain   not saved Successfully");
    }

    setFormSubmitted(true);
  };

  const handleSearch = async () => {
    setLoading(true);

    // const token = localStorage.getItem("token");
    // const user_id = localStorage.getItem("user_id");

    try {
      const user1 = {
        user_unique_id: id,
        endpoint: "getExternalLinkLiveTendersData",
        search: `${formData.search}`,
      };
      const response1 = await axios.post(
        `${process.env.NEXT_PUBLIC_API_BASEURL}` +
          "external-link-live-tenders.php",
        user1
      );
      localStorage.setItem("search", formData?.search);
      setData(Object.values(response1.data.data.tenders));
      setLink(Object.values(response1.data.data.links));
    } catch (error) {
      console.error(error);
    } finally {
      setLoading(false);
    }

    // setSearch('');
  };

  const paginationLink = async (pageNo = 1) => {
    setLoading(true);
    if (typeof window !== "undefined") {
      window.scrollTo({ top: 0, left: 0, behavior: "smooth" });
    }
    if (typeof pageNo === "string") {
    } else {
      if (typeof window !== "undefined") {
        const token = localStorage.getItem("token");
        // const user_id = localStorage.getItem('user_id');

        try {
          const user1 = {
            user_unique_id: id,
            token: token,
            page_no: pageNo,
            search: formData?.search,
            endpoint: "getExternalLinkLiveTendersData",
          };
          const response1 = await axios.post(
            `${process.env.NEXT_PUBLIC_API_BASEURL}` +
              "external-link-live-tenders.php",
            user1
          );
          setUser(response1.data.data);
          setData(Object.values(response1.data.data.tenders));
          setLink(Object.values(response1.data.data.links));
        } catch (error) {
          console.error(error);
        } finally {
          setLoading(false);
        }
      }
    }
  };

  useEffect(() => {
    if (formRef.current) {
      // formRef.current.parsley();
    }
    const search = localStorage.getItem("search");
    setFormData({ ...formData, search: search });
    const validateLogin = async () => {
      setLoading(true);
      if (typeof window !== "undefined") {
        const token = localStorage.getItem("token");
        // const id = localStorage.getItem('user_id');

        try {
          const search = localStorage.getItem("search");
          const user1 = {
            user_unique_id: id,
            token: token,
            ...(search && { search }),
            endpoint: "getExternalLinkLiveTendersData",
            id: "",
          };
          console.log(id);
          const response1 = await axios.post(
            `${process.env.NEXT_PUBLIC_API_BASEURL}` +
              "external-link-live-tenders.php",
            user1
          );
          if (response1.data.status === " success") {
            setUser(response1.data.data);
            setData(Object.values(response1.data.data.tenders));
            setLink(Object.values(response1.data.data.links));
          } else {
            if (typeof window !== "undefined") {
              window.location.href = "/tender18";
            }
          }
        } catch (error) {
          console.error(error);
        } finally {
          setLoading(false);
        }
      }
    };
    validateLogin();
  }, []);

  return (
    <>
      {loading ? (
        <div className="loader">
          <img src="/images/Iphone-spinner-2.gif" alt="" />
        </div>
      ) : (
        <div className="login-user">
          <div className="container-main">
            <div className="login-user-title">
              <h1>
                <span>All Live</span> TENDERS
              </h1>
              <h6>{user.user_name}</h6>
              <h6>{user.user_email}</h6>
            </div>
            
            <div className="login-user-search">
              <div className="user-key-btn d-flex gap-2">
                <a data-bs-toggle="modal" data-bs-target="#tender_modal" onClick={handleShow}>Feedback</a>
                <a data-bs-toggle="modal" data-bs-target="#complain_modal" onClick={handleCShow} style={{ ["marginRight"]: "15px" }}>Complain</a>   
              </div>
              <div className="login-user-input">
                <input
                  type="text"
                  name="search"
                  value={formData?.search}
                  onChange={handleChange}
                  placeholder="Search..."
                />
              </div>
              <div className="login-search">
                <button onClick={handleSearch}>
                  <i className="fa-solid fa-magnifying-glass"></i>
                </button>
              </div>
            </div>
            <div className="tender-modal">
                  <Modal
                    show={show}
                    onHide={handleClose}
                    className="tenders-modal"
                    id="tender_modal"
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
                              value={fformData.username}
                              required
                            />
                          </div>
                          <div className="why-us-form-block">
                            <input
                              type="email"
                              placeholder="Email"
                              name="email"
                              onChange={handleChange}
                              value={fformData.email}
                              required
                            />
                          </div>
                          <div className="why-us-form-block">
                            <input
                              type="number"
                              placeholder="Mobile"
                              name="mobile"
                              onChange={handleChange}
                              value={fformData.mobile}
                              required
                            />
                          </div>

                          <div className="why-us-form-block">
                            <textarea
                              placeholder="Description"
                              name="description"
                              row="3"
                              onChange={handleChange}
                              value={fformData.description}
                              required
                            ></textarea>
                          </div>
                        </div>

                        <div className="why-us-form-submit">
                          <input type="submit" value="Submit" />
                        </div>
                      </form>

                      {submitMessage && (
                        <p className="submit-text">{submitMessage}</p>
                      )}
                    </Modal.Body>
                  </Modal>
            </div>
            <div className="complain-modal">
                <Modal
                  show={cshow}
                  onHide={handleCClose}
                  className="tenders-modal"
                  id="complain_modal"
                  centered
                >
                  <Modal.Header closeButton></Modal.Header>
                  <Modal.Body className="tenders-modal-body">
                    <form
                      onSubmit={handleCSubmit}
                      ref={formRef}
                      className="tender-inquiry"
                    >
                      <div className="why-us-form-flex">
                        <div className="why-us-form-block">
                          <input
                            type="text"
                            placeholder="Name"
                            name="username"
                            onChange={handleCChange}
                            value={cformData.username}
                            required
                          />
                        </div>
                        <div className="why-us-form-block">
                          <input
                            type="email"
                            placeholder="Email"
                            name="email"
                            onChange={handleCChange}
                            value={cformData.email}
                            required
                          />
                        </div>
                        <div className="why-us-form-block">
                          <input
                            type="number"
                            placeholder="Mobile"
                            name="mobile"
                            onChange={handleCChange}
                            value={cformData.mobile}
                            required
                          />
                        </div>

                        <div className="why-us-form-block">
                          <textarea
                            placeholder="Description"
                            name="description"
                            row="3"
                            onChange={handleCChange}
                            value={cformData.description}
                            required
                          ></textarea>
                        </div>
                      </div>

                      <div className="why-us-form-submit">
                        <input type="submit" value="Submit" />
                      </div>
                    </form>

                    {submitMessage && (
                      <p className="submit-text">{submitMessage}</p>
                    )}
                  </Modal.Body>
                </Modal>
            </div>
            <div className="tenders-list-main user-tenders-list-main">
              <div className="user-live-tenders-flex">
                {data.map((tendersdata, index) => {
                  return (
                    <div className="user-live-tenders-block mb-4" key={index}>
                      <div className="live-tenders-block-inner">
                        <div className="tenders-top-flex">
                          <div className="tenders-top-left">
                            <h6>
                              T18 Ref No : <span>{tendersdata.ref_no}</span>
                            </h6>
                          </div>

                          <div className="location">
                            <h6>
                              Location : <span>{tendersdata.location}</span>
                            </h6>
                          </div>
                        </div>

                        <div className="tender-work">
                          <h4>
                            <Link
                              href={`/tenders-details/${tendersdata.ref_no}/${id}`}
                              target="_blank"
                              dangerouslySetInnerHTML={{
                                __html: tendersdata.title,
                              }}
                            ></Link>
                            {/* <Link href={`/tender18/tenders-details?id=${tendersdata.ref_no}`} dangerouslySetInnerHTML={{ __html:tendersdata.title}}>
                        </Link> */}
                          </h4>
                        </div>

                        <hr />

                        <div className="tender-bottom-flex">
                          <div className="tenders-top-left">
                            <h6>
                              Agency / Dept : <span>{tendersdata.agency}</span>
                            </h6>
                          </div>
                          <div className="tenders-top-right">
                            <h6>
                              Tender Value :{" "}
                              <span>{tendersdata.tender_value}</span>
                            </h6>
                          </div>
                        </div>

                        <div className="tenders-top-flex">
                          <div className="due-date">
                            <h6>
                              Due Date : <span>{tendersdata.due_date}</span>
                            </h6>
                          </div>
                          <div className="tenders-top-right">
                            <Link
                              href={`/tenders-details/${tendersdata.ref_no}/${id}`}
                              target="_blank"
                            >
                              View Documents
                            </Link>
                            {/* <Link href={`https://api.whatsapp.com/send?phone=917069661818&text=I would like to inquire about Tender Ref No : ${tendersdata.ref_no}`} target="_blank">
                            <img src={Icon}></img>
                        </Link>*/}
                          </div>
                        </div>
                      </div>
                    </div>
                  );
                })}
              </div>

              <div className="tenders-pagination">
                <ul>
                  {link.map((linkdata, index) => {
                    return (
                      <li key={index}>
                        <button
                          dangerouslySetInnerHTML={{ __html: linkdata }}
                          onClick={() => paginationLink(linkdata)}
                        ></button>
                      </li>
                    );
                  })}
                </ul>
              </div>
            </div>
          </div>
        </div>
      )}
    </>
  );
};

export default AfterLoginLiveTenders;
