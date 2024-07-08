"use client";
import { useEffect, useRef, useState } from "react";
import axios from "axios";
import Link from "next/link";
// import "parsleyjs";

const HomeLiveTender = () => {
  const [data, setData] = useState([]);
  const [dataDetails, setDataDetails] = useState([]);
  console.log({ dataDetails });
  const [tdata, setTdata] = useState([]);
  const [isLoggedIn, setIsLoggedIn] = useState(false);
  console.log({ isLoggedIn });

  // const [show, setShow] = useState(false);
  // const [state, setState] = useState();
  // const [loading, setLoading] = useState(true);
  // const [submitMessage, setSubmitMessage] = useState(null);
  // const [formData, setFormData] = useState({
  //   username: '',
  //   company_name: '',
  //   email: '',
  //   mobile: '',
  //   state: '',
  //   description: ''
  // });
  const formRef = useRef(null);
  // const inputRef = useRef(null);

  // const handleClose = () => setShow(false);
  // const handleShow = () => setShow(true);

  // const handleChange = (e) => {
  //   const { name, value } = e.target;
  //   setFormData((prevData) => ({ ...prevData, [name]: value }));
  // };

  // const handleSubmit = async (e) => {
  //   e.preventDefault();

  // if(formData.trim() === ''){
  //   inputRef.current.classList.add('invalid');
  // }

  //   const user = {
  //     name: `${formData.username}`,
  //     company_name: `${formData.company_name}`,
  //     email: `${formData.email}`,
  //     mobile: `${formData.mobile}`,
  //     state: `${formData.state}`,
  //     description: `${formData.description}`,
  //     endpoint: 'saveRegistrationData'
  //   }
  //   try {
  //     const alert = await axios.post(`${process.env.NEXT_PUBLIC_API_BASEURL}`+'registration-form.php', user);
  //     // console.log(alert.data.status);

  //     if (alert.data.status == ' success') {
  //       setSubmitMessage('Mail Sent Successfully');

  //       setFormData({
  //         username: '',
  //         company_name: '',
  //         email: '',
  //         mobile: '',
  //         state: '',
  //         description: ''
  //       });
  //     }

  //     else {
  //       setSubmitMessage('Mail Not Sent Successfully');
  //     }
  //   }
  //   catch (error) {
  //     setSubmitMessage('Mail Not Sent Successfully');
  //   }
  // };

  useEffect(() => {
    if (formRef.current) {
      // $(formRef.current).parsley();
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
              // console.log("login");
              const user1 = {
                user_unique_id: user_id,
                token: token,
                endpoint: "getUserNewTendersData",
              };
              const response1 = await axios.post(
                `${process.env.NEXT_PUBLIC_API_BASEURL}` +
                  "new-tenders/user-new-tenders.php",
                user1
              );
              setTdata(Object.values(response1.data.data.tenders));
            } else {
              // console.log("guest");
              setIsLoggedIn(false);
              const response2 = await axios.get(
                `${process.env.NEXT_PUBLIC_API_BASEURL}` +
                  "new-tenders/new-tenders.php?endpoint=getNewTendersData"
              );
              setTdata(Object.values(response2.data.data.tenders));
            }
          } catch (error) {
            console.error(error);
          } finally {
            // setLoading(false);
          }
        } else {
          setIsLoggedIn(false);
          // console.log("guest");
          const response3 = await axios.get(
            `${process.env.NEXT_PUBLIC_API_BASEURL}` +
              "new-tenders/new-tenders.php?endpoint=getNewTendersData"
          );
          setTdata(Object.values(response3.data.data.tenders));
        }
      }
    };
    validateLogin();

    const fetchData = async () => {
      try {
        const response = await axios.get(
          `${process.env.NEXT_PUBLIC_API_BASEURL}` +
            "homepage/live-tenders-section.php?endpoint=getLiveTendersData"
        );
        setData(response.data.data.main);
        setDataDetails(Object.values(response.data.data.details));
      } catch (error) {
        console.error("Error fetching data:" + error);
      }
      // finally {
      //   setLoading(false);
      // }
    };

    fetchData();
  }, []);

  return (
    <>
      <div className="live-tenders">
        <div className="container-main">
          <div className="services-title text-center">
            <h2>{data.title}</h2>
            <p>{data.description}</p>
          </div>

          <div className="live-tenders-flex">
            {tdata.map((tendersdata, index) => {
              return (
                <div className="live-tenders-block" key={index}>
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
                          href={`/tenders-details/${tendersdata.ref_no}`}
                          dangerouslySetInnerHTML={{
                            __html: tendersdata.title,
                          }}
                          target="_blank"
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
                          Tender Value : <span>{tendersdata.tender_value}</span>
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
                          href={`/tenders-details/${tendersdata.ref_no}`}
                          target="_blank"
                        >
                          View Documents
                        </Link>
                        <Link
                          href={`https://api.whatsapp.com/send?phone=917069661818&text=I would like to inquire about Tender Ref No : ${tendersdata.ref_no}`}
                          target="_blank"
                        >
                          <img src={"/images/whatsapp-icon.webp"} alt="Tender18 Infotech"></img>
                        </Link>
                      </div>
                    </div>
                  </div>
                </div>
              );
            })}
          </div>
        </div>
      </div>

      {/* <div className="tender-modal">
        <Modal show={show} onHide={handleClose} className='tenders-modal'>
          <Modal.Header closeButton>
            <Modal.Title></Modal.Title>
          </Modal.Header>
          <Modal.Body className='tenders-modal-body'>
            <form onSubmit={handleSubmit} ref={formRef} data-parsley-validate>
              <div className='tenders-body-flex'>
                <div className="contact-page-block">
                  <label htmlFor="">Name</label>
                  <input type="text" placeholder='Name' name="username" onChange={handleChange} value={formData.username} data-parsley-required="true" ref={inputRef}/>
                </div>

                <div className="contact-page-block">
                  <label htmlFor="">Company Name</label>
                  <input type="text" placeholder='Enter Your Company Name' name='company_name' onChange={handleChange} value={formData.company_name}/>
                </div>

                <div className="contact-page-block">
                  <label htmlFor="">Mobile</label>
                  <input type="number" placeholder='Mobile' name='mobile' onChange={handleChange} value={formData.mobile} data-parsley-required="true" ref={inputRef}/>
                </div>

                <div className="contact-page-block">
                  <label htmlFor="">Email</label>
                  <input type="email" placeholder='Email' name='email' onChange={handleChange} value={formData.email} data-parsley-required="true" ref={inputRef}/>
                </div>
              </div>


              <div className="contact-page-submit">
                <input type="submit" value="Submit" />
              </div>
            </form>

            {submitMessage && <p className='submit-text'>{submitMessage}</p>}
          </Modal.Body>
        </Modal>
      </div> */}
    </>
  );
};

export default HomeLiveTender;
