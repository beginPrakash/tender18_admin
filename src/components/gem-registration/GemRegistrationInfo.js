"use client";
import React, { useEffect, useState, useRef } from "react";
import Accordion from "react-bootstrap/Accordion";
import axios from "axios";
import { GemRegistrationData1 } from "./GemRegistrationData";
import StateData from "@/static-data/StateData";

const GemRegistrationInfo = () => {
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

  return (
    <>
      <div className="gem-registraton-main">
        <div className="container-main">
          <div className="gem-registration-top-flex">
            <div className="gem-registration-top-left">
              {GemRegistrationData1.map((gemdata, index) => {
                return (
                  <div className="gem-registration-block" key={index}>
                    <h2>{gemdata.title}</h2>
                    <h6>{gemdata.subtitile}</h6>
                    <p>
                      Do you want to bid on government contracts but don't know
                      how? Then, we are your go-to GeM portal service provider
                      for quick, affordable, and high-quality Government
                      e-Marketplace services. Tender18 is a government
                      e-Marketplace (GeM) registration consultant that offers a
                      simple, fast, and easy-to-use service to register and
                      participate in government tenders. We help businesses with
                      the entire registration process on the GeM portal from
                      start to finish. In addition, we offer a wide range of
                      services that businesses can use to streamline their
                      operations, including online tender submission, contract
                      management, document management, and more.
                    </p>
                    <p>
                      Tender18's GeM registration service is designed to help
                      businesses save time and money by simplifying the process
                      of registering for government tenders. We also offer
                      ongoing support to our clients with any issues that may
                      arise during the registration process.
                    </p>
                    <h3>How To Get GeM Registration With Tender18s ?</h3>
                    <div className="gem-registration-inner-flex">
                      <div className="gem-registration-inner-block">
                        <img src="/images/gem-get-touch.webp" alt="GetIcon" />
                        <p>
                          Step 1 : Get in touch with a Tender18 Representative
                        </p>
                      </div>
                      <div className="gem-registration-inner-block">
                        <img
                          src="/images/get-share-documents.webp"
                          alt="GetShare"
                        />
                        <p>Step 2 : Share the required documents and details</p>
                      </div>
                      <div className="gem-registration-inner-block">
                        <img src="/images/get-registration.webp" alt="GetReg" />
                        <p>Step 3 : Get your GeM registration in 3-5 days</p>
                      </div>
                    </div>
                    <h3>Elevate Your Business On GeM Portal</h3>
                    <p>
                      Sell your products and services to genuine government
                      buyers. Let us help you become an authorized vendor on the
                      GeM Portal.
                    </p>
                  </div>
                );
              })}
              <div className="gem-top-bottom-flex">
                <div className="gem-top-bottom-left">
                  <h3>Why Choose The Tender As Your GeM Consultant</h3>
                  <ul>
                    <li>
                      Our professionals are experienced in this field and can
                      provide end-to-end guidance
                    </li>
                    <li>We provide quick and efficient customer support</li>
                    <li>You get a dedicated account manager</li>
                    <li>You get a range of services under one umbrella</li>
                    <li>We keep your data confidential</li>
                    <li>
                      We are already serving approx. 500 clients across India
                    </li>
                  </ul>
                </div>

                <div className="gem-top-bottom-right">
                  <img src="/images/gem-img1.webp" alt="Img1" />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div className="gem-registration-parellex">
        <div className="container-main">
          <div className="gem-registration-parellex-width">
            <div className="gem-registration-parellex-info">
              <h2>
                Looking For An Expert To Help You With The Government
                E-Marketplace?
              </h2>
              <p>
                Get all your work done by our professionals while you relax. Our
                team will complete all your tasks within the timeframe you
                specify. Our experts will handle everything for you, so you need
                not worry about a thing. We are ready to assist you with any
                questions.
              </p>
              <p>
                For further queries, call{" "}
                <a href="tel:+91 7069661818">+91 7069661818</a> and speak to an
                expert or mail us at{" "}
                <a href="mailto:sales@tender18.com">
                  sales@tender18.com / wecare@tender18.com
                </a>
              </p>
            </div>
          </div>
        </div>
        <div className="gem-registration-parellex-overlay"></div>
      </div>

      <div className="gem-registration-bottom">
        <div className="container-main">
          <div className="gem-registration-bottom-width">
            <div className="gem-registration-bottom-title">
              <h2>Get The Best Consultants For GeM Portal</h2>
            </div>
            <div className="gem-registration-bottom-flex">
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
              <div className="gem-registration-bottom-block">
                <h3>Become A Registered Seller on Government e-Marketplace</h3>
                <p>
                  Get a host of services all under one umbrella. Whether you
                  need assistance with tax assessment verification or office
                  location verification, we can help you complete the process.
                </p>
                <ul>
                  <li>Vendor Registration</li>
                  <li>Profile Updating</li>
                  <li>Vendor Assessment</li>
                  <li>QCI Profile Updating</li>
                  <li>Reseller Panel Support</li>
                  <li>OEM Panel Support</li>
                  <li>Service/Product Catalogue</li>
                  <li>Service/Product Category Management</li>
                  <li>Category Brand Management</li>
                  <li>Product Specification Management</li>
                  <li>Category Brand Management</li>
                  <li>Product Specification Management</li>
                  <li>Service Tender Bidding Support</li>
                  <li>Product Tender Bidding Support</li>
                  <li>Invoice Generation/ Order Management</li>
                </ul>
                <p>
                  The Tenders make GeM registration easy. We help you understand
                  the process, arrange the required documents and complete the
                  registration process. If you want to register on the
                  Government e-Marketplace as a vendor, we can help you with
                  everything you need to get started. Contact us today for more
                  information.
                </p>
              </div>
            </div>

            <div className="gem-faqs">
              <Accordion defaultActiveKey="0">
                <Accordion.Item eventKey="0">
                  <Accordion.Header>What is a GeM Portal?</Accordion.Header>
                  <Accordion.Body>
                    <p>
                      <b>GeM Portal tender</b> is an official website developed
                      and controlled by the Government via which the Government
                      provides tender details for various requirements of the
                      Government, ministries, and departments. The portal is
                      designed and developed to provide single-window access to
                      the Government's information and services to various
                      beneficiaries/citizens in India.
                    </p>
                    <p>
                      The GeM Portal tender is one of the leading platforms used
                      by the Government for procurement needs throughout India.
                      As such, it provides complete transparency to all
                      stakeholders involved in public sector organizations. In
                      addition, it is an online portal for state government
                      agencies to procure products and services.Businesses can
                      connect with Tender18.com experts to seek help with vendor
                      registration, profile updating, catalog and category
                      management, and service and product tender bidding.
                    </p>
                  </Accordion.Body>
                </Accordion.Item>
                <Accordion.Item eventKey="1">
                  <Accordion.Header>What is GeM Registration?</Accordion.Header>
                  <Accordion.Body>
                    <p>
                      <b>GeM Registration</b> is a process that allows suppliers
                      to register on the GeM portal and participate in GeM
                      tenders. GeM (Government e-Marketplace) is an online
                      marketplace where government buyers can find and compare
                      products and services from registered suppliers.
                    </p>
                    <p>
                      GeM portal registration is quick and easy, and once
                      registered, suppliers can participate in GeM tenders to
                      win government contracts. GeM tenders are typically
                      published on the GeM portal, and interested suppliers can
                      submit bids online
                    </p>
                  </Accordion.Body>
                </Accordion.Item>
                <Accordion.Item eventKey="2">
                  <Accordion.Header>
                    How to Register on the GeM marketplace?
                  </Accordion.Header>
                  <Accordion.Body>
                    <p>
                      The GeM marketplace was launched to streamline the
                      procurement process and make it more efficient. However,
                      if you are a businessman who wants to start selling on
                      GeM, then you will need to register on the portal first.
                      You can talk to experts Tender18, who can speed up the
                      registration process. We've been doing this for a long
                      time, so we know all the little details.
                    </p>
                    <ul className="numberd-list">
                      <li>
                        Go to the official website of GeM (gem.gov.in) and click
                        on the "Register" button at the top right-hand side of
                        the homepage.
                      </li>
                      <li>
                        Fill out the registration form with all the required
                        information, such as your name, company name, email
                        address, mobile number, etc.
                      </li>
                      <li>
                        Once you have filled out the form, click on the "Submit"
                        button.
                      </li>
                      <li>
                        Enter the OTP received on your registered mobile number
                        and click "Verify".
                      </li>
                      <li>
                        After verifying your mobile number, you will be asked to
                        create a password for your account. Choose a strong
                        password comprising at least 8 characters, including
                        uppercase and lowercase letters, numbers, and special
                        symbols.
                      </li>
                      <li>
                        Once you have created your password, click the "Submit"
                        button.
                      </li>
                      <li>
                        Congratulations! You have now successfully registered on
                        the GeM portal.
                      </li>
                    </ul>
                  </Accordion.Body>
                </Accordion.Item>
                <Accordion.Item eventKey="3">
                  <Accordion.Header>
                    Product Catalogue and Category Management
                  </Accordion.Header>
                  <Accordion.Body>
                    <p>
                      After successfully registering with the GeM portal, the
                      next step is to create your product and service catalog.
                      The GeM Portal has over 100 categories, but we sometimes
                      need help finding the right one for our products. On the
                      GeM portal, you can contact an expert to assist you in
                      managing your category and products.
                    </p>
                    <p>
                      Tender18 consultants offer the best services for managing
                      your profile on the GeM online portal. Because the experts
                      have years of experience handling full-circle processing
                      on the GeM portal, you can expect quick and satisfactory
                      service.
                    </p>
                  </Accordion.Body>
                </Accordion.Item>
                <Accordion.Item eventKey="4">
                  <Accordion.Header>
                    Support for L1 Procurement on GeM Online Portal
                  </Accordion.Header>
                  <Accordion.Body>
                    <p>
                      L1 procurement allows for direct orders ranging from INR
                      25,000 to INR 5,00,000. On GeM, the buyer must compare
                      three OEMs or Service Providers that meet the demand for
                      quality, quantity, specifications, and delivery period.
                      The system will then recommend an L1 service provider that
                      meets these criteria.
                    </p>
                    <p>
                      To learn how a buyer can do this easily, contact
                      consultants at Tender18.com. The experts can assist buyers
                      in making direct purchases on the GeM portal by guiding
                      them through the proper process.
                    </p>
                  </Accordion.Body>
                </Accordion.Item>
                <Accordion.Item eventKey="5">
                  <Accordion.Header>
                    GeM Registration Fees & Other Details
                  </Accordion.Header>
                  <Accordion.Body>
                    <p>
                      The GeM online portal is an excellent way for people in
                      business to sell their products and services to the
                      Government or private sector. However, to start selling on
                      GeM, you must register on the portal first.GeM
                      registration fees apply to all new users registering on
                      the GeM marketplace. The GeM online registration process
                      is quick and easy and can be completed online. After
                      registering, users will be required to pay a one-time fee
                      called caution money. This fees is a security deposite to
                      reduce any unethical process from seller account. This is
                      same like a wallet of seller account.
                    </p>
                  </Accordion.Body>
                </Accordion.Item>
                <Accordion.Item eventKey="6">
                  <Accordion.Header>
                    Advantage As a Seller and Buyer on Gem Portal
                  </Accordion.Header>
                  <Accordion.Body>
                    <p>
                      GeM portal gives all registered suppliers / sellers equal
                      opportunity to offer their products / services as per
                      their capability across all categories. GeM offers an
                      assurance of quality, delivery, warranty and pricing
                      transparency to the buyers through the standardization of
                      product attributes, Seller Assurance Program and Buyer
                      Assurance Program, respectively. To instill confidence
                      among buyers about sellers’ credentials on GeM, a
                      provision has been made for Seller’s Rating by buyers,
                      which is visible to other potential buyers. Furthermore,
                      geM supports Online bidding in the open category and
                      Reverse Auction in the General insurance category. These
                      processes help in bringing more competition, thereby
                      resulting in cost savings for the buyer organization.
                    </p>
                    <p>
                      GeM supports multiple payment options like Online banking
                      (NEFT/RTGS), which are highly secure and convenient
                      compared to traditional methods like cheques / DDs etc.,
                      thereby saving time & cost involved in processing these
                      traditional payment instruments. Moreover, the entire
                      payment mechanism on GeM is automated through
                      SGST-compliant invoicing, ensuring seamless integration
                      with GSTN for both buyer & seller organizations.A unique
                      feature on the GeM portal is that payments to GeM
                      registered sellers are released within 03 days* of receipt
                      of material / services by the buyer organization subject
                      to submission of required documents as per terms &
                      conditions of contract by the seller organization, i.e.,
                      invoice, packing list etc. Compared to traditional
                      purchase systems, this ensures faster realization of
                      payments for the seller organizations, thereby improving
                      their cash flows and business efficiency.
                    </p>
                  </Accordion.Body>
                </Accordion.Item>
                <Accordion.Item eventKey="7">
                  <Accordion.Header>
                    Documents Required as A Seller & Buyer
                  </Accordion.Header>
                  <Accordion.Body>
                    <ul>
                      <li>Your pan card</li>
                      <li>Your Aadhar card</li>
                      <li>Your bank account details</li>
                    </ul>
                  </Accordion.Body>
                </Accordion.Item>
                <Accordion.Item eventKey="8">
                  <Accordion.Header>
                    As a buyer, you will need to provide the following documents
                  </Accordion.Header>
                  <Accordion.Body>
                    <ul>
                      <li>Your pan card</li>
                      <li>Your Aadhar card</li>
                      <li>Your bank account details</li>
                      <li>
                        A NOC from your current gas supplier (if applicable)
                      </li>
                    </ul>
                  </Accordion.Body>
                </Accordion.Item>
                <Accordion.Item eventKey="9">
                  <Accordion.Header>
                    What are the other services offered by The Tenders?
                  </Accordion.Header>
                  <Accordion.Body>
                    <ul>
                      <li>Upcoming Projects Information</li>
                      <li>Tender Result or Tender Award</li>
                      <li>Sub-Contracting (Get your work done)</li>
                      <li>Sub-Contractors (Get Work)</li>
                      <li>Digital Signature Certificate</li>
                      <li>
                        Technical support for Digital Signature Certificate
                      </li>
                      <li>Online support for Digital Signature Certificate</li>
                      <li>Bid form collection</li>
                      <li>Bid form filling</li>
                      <li>Bid form submission</li>
                    </ul>
                  </Accordion.Body>
                </Accordion.Item>
              </Accordion>
            </div>
          </div>
        </div>
      </div>
    </>
  );
};

export default GemRegistrationInfo;
