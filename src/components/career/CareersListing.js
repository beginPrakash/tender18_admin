"use client";
import React, { useState, useRef } from "react";
import { Modal } from "react-bootstrap";
import CareersListingData from "./CareersListingData";

const CareersListing = () => {
  const [show, setShow] = useState(false);
  const [show1, setShow1] = useState(false);

  const handleClose = () => setShow(false);
  const handleShow = () => setShow(true);
  const handleClose1 = () => setShow1(false);
  const handleShow1 = () => setShow1(true);

  // $(document).on("click", ".careers-block-btn .careers-btn", function () {
  //   var target = $(this).attr("data-bs-target");
  //   $(".career-modal").css("display", "none");
  //   $(target).parent().css("display", "block");
  //   setTimeout(function () {
  //     $(".career-modal").css("display", "none");
  //     $(target).parent().css("display", "block");
  //   }, 100);
  //   setTimeout(function () {
  //     $(".career-modal").css("display", "none");
  //     $(target).parent().css("display", "block");
  //   }, 300);
  //   setTimeout(function () {
  //     $(".career-modal").css("display", "none");
  //     $(target).parent().css("display", "block");
  //   }, 500);
  // });

  return (
    <>
      <div className="careers-listing-main">
        <div className="container-main">
          <div className="careers-listing-width">
            <div className="careers-listing-main">
              {CareersListingData.map((listingdata, index) => {
                return (
                  <div className="careers-listing-block" key={index}>
                    <div className="careers-block-title">
                      <h6>{listingdata.time}</h6>
                      <h4>{listingdata.title}</h4>
                    </div>
                    <div className="careers-block-desc">
                      <p>{listingdata.desc}</p>
                    </div>

                    <div className="careers-block-btn">
                      <a
                        type="button"
                        className="careers-btn"
                        data-bs-toggle="modal"
                        data-bs-target={listingdata.target1}
                        onClick={handleShow}
                      >
                        {listingdata.btn1}
                      </a>
                      <Modal
                        show={show}
                        onHide={handleClose}
                        id={listingdata.id1}
                        className="career-modal"
                        tabIndex={listingdata.index}
                      >
                        <Modal.Header closeButton>
                          <Modal.Title>{listingdata.modaltitle}</Modal.Title>
                        </Modal.Header>
                        <Modal.Body className="career-modal-body">
                          <p>
                            {listingdata.experience}&nbsp;| &nbsp;
                            {listingdata.openings}
                          </p>
                          <h3>Job Profile</h3>
                          <ul>
                            <li>{listingdata.li1}</li>
                            <li>{listingdata.li2}</li>
                            <li>{listingdata.li3}</li>
                            <li>{listingdata.li4}</li>
                            <li>{listingdata.li5}</li>
                            <li>{listingdata.li6}</li>
                            <li>{listingdata.li7}</li>
                            <li>{listingdata.li8}</li>
                            <li>{listingdata.li9}</li>
                          </ul>

                          <h3>Annual Package</h3>
                          <ul>
                            <li>{listingdata.package}</li>
                          </ul>

                          <h3>Location:</h3>
                          <ul>
                            <li>{listingdata.location}</li>
                          </ul>
                        </Modal.Body>
                      </Modal>

                      <a
                        type="button"
                        className="careers-btn"
                        data-bs-toggle="modal"
                        data-bs-target={listingdata.target2}
                        onClick={handleShow1}
                      >
                        {listingdata.btn2}
                      </a>
                      <Modal
                        show={show1}
                        onHide={handleClose1}
                        id={listingdata.id2}
                        className="career-modal"
                      >
                        <Modal.Header closeButton>
                          <Modal.Title>{listingdata.modaltitle}</Modal.Title>
                        </Modal.Header>
                        <Modal.Body className="career-modal-body">
                          <form>
                            <div className="career-modal-form-block">
                              <input type="text" placeholder="Name"></input>
                            </div>
                            <div className="career-modal-form-block">
                              <input type="email" placeholder="Email"></input>
                            </div>
                            <div className="career-modal-form-block">
                              <input type="number" placeholder="Number"></input>
                            </div>
                            <div className="career-modal-form-block">
                              <textarea placeholder="Message"></textarea>
                            </div>
                            <div className="career-modalform-upload-block">
                              <label
                                className="button-upload"
                                htmlFor="inputtag3"
                              >
                                Upload a file
                                <input
                                  type="file"
                                  id="inputtag3"
                                  accept=".doc,.docx,.pdf"
                                ></input>
                              </label>
                              <span>*Must Upload CV in Word or PDF format</span>
                            </div>
                            <div className="career-modal-submit">
                              <input type="submit" value="Submit"></input>
                            </div>
                          </form>
                        </Modal.Body>
                      </Modal>
                    </div>
                  </div>
                );
              })}
            </div>
          </div>
        </div>
      </div>
    </>
  );
};

export default CareersListing;
