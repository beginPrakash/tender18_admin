"use client";
import React, { useState } from "react";
import { DateRangePicker } from "rsuite";
import Button from "react-bootstrap/Button";
import Offcanvas from "react-bootstrap/Offcanvas";
import "rsuite/dist/rsuite.min.css";
// import 'rsuite/dist/styles/rsuite.min.css';
// import 'rsuite/dist/styles/rsuite.min.css';
// import 'rsuite/dist/rsuite.min.css';

const LiveTendersFilters = ({ ...props }) => {
  const [show, setShow] = useState(false);

  const handleClose = () => setShow(false);
  const handleShow = () => {
    setShow(true);
  };

  return (
    <>
      <div className="tenders-filters">
        <div className="filters-title">
          <h4>Filters By:</h4>
        </div>
        <div className="tenders-filters-main">
          <div className="tenders-filters-block">
            <h6>T18 Ref No</h6>
            <input type="text" placeholder="Enter T18 Ref No..." />
          </div>

          <div className="tenders-filters-block">
            <h6>Keyword</h6>
            <input
              type="text"
              name="tags"
              data-role="tagsinput"
              placeholder="Enter Keyword"
            />
          </div>
          <div className="tenders-filters-block">
            <h6>State</h6>
            <div className="filters-checkbox">
              <label htmlFor="andman">
                Andaman and Nicobar Islands
                <input type="checkbox" id="andman" />
                <span className="checkmark"></span>
              </label>

              <label htmlFor="andhra">
                Andhra Pradesh
                <input type="checkbox" id="andhra" />
                <span className="checkmark"></span>
              </label>

              <label htmlFor="Arunachal">
                Arunachal Pradesh
                <input type="checkbox" id="Arunachal" />
                <span className="checkmark"></span>
              </label>

              <label htmlFor="Assam">
                Assam
                <input type="checkbox" id="Assam" />
                <span className="checkmark"></span>
              </label>

              <label htmlFor="Bihar">
                Bihar
                <input type="checkbox" id="Bihar" />
                <span className="checkmark"></span>
              </label>

              <label htmlFor="Chandigarh">
                Chandigarh
                <input type="checkbox" id="Chandigarh" />
                <span className="checkmark"></span>
              </label>

              <label htmlFor="Chhattisgarh">
                Chhattisgarh
                <input type="checkbox" id="Chhattisgarh" />
                <span className="checkmark"></span>
              </label>

              <label htmlFor="Dadra">
                Dadra and Nagar Haveli
                <input type="checkbox" id="Dadra" />
                <span className="checkmark"></span>
              </label>

              <label htmlFor="Daman">
                Daman And Diu
                <input type="checkbox" id="Daman" />
                <span className="checkmark"></span>
              </label>

              <label htmlFor="Delhi">
                Delhi
                <input type="checkbox" id="Delhi" />
                <span className="checkmark"></span>
              </label>

              <label htmlFor="Goa">
                Goa
                <input type="checkbox" id="Goa" />
                <span className="checkmark"></span>
              </label>

              <label htmlFor="Gujarat">
                Gujarat
                <input type="checkbox" id="Gujarat" />
                <span className="checkmark"></span>
              </label>

              <label htmlFor="Haryana">
                Haryana
                <input type="checkbox" id="Haryana" />
                <span className="checkmark"></span>
              </label>

              <label htmlFor="Himachal">
                Himachal Pradesh
                <input type="checkbox" id="Himachal" />
                <span className="checkmark"></span>
              </label>

              <label htmlFor="Jammu">
                Jammu & Kashmir
                <input type="checkbox" id="Jammu" />
                <span className="checkmark"></span>
              </label>

              <label htmlFor="Jharkhand">
                Jharkhand
                <input type="checkbox" id="Jharkhand" />
                <span className="checkmark"></span>
              </label>

              <label htmlFor="Karnataka">
                Karnataka
                <input type="checkbox" id="Karnataka" />
                <span className="checkmark"></span>
              </label>

              <label htmlFor="Kerala">
                Kerala
                <input type="checkbox" id="Kerala" />
                <span className="checkmark"></span>
              </label>

              <label htmlFor="Ladakh">
                Ladakh
                <input type="checkbox" id="Ladakh" />
                <span className="checkmark"></span>
              </label>

              <label htmlFor="Lakshadweep">
                Lakshadweep
                <input type="checkbox" id="Lakshadweep" />
                <span className="checkmark"></span>
              </label>

              <label htmlFor="mp">
                Madhya Pradesh
                <input type="checkbox" id="mp" />
                <span className="checkmark"></span>
              </label>

              <label htmlFor="Maharashtra">
                Maharashtra
                <input type="checkbox" id="Maharashtra" />
                <span className="checkmark"></span>
              </label>

              <label htmlFor="Manipur">
                Manipur
                <input type="checkbox" id="Manipur" />
                <span className="checkmark"></span>
              </label>

              <label htmlFor="Meghalaya">
                Meghalaya
                <input type="checkbox" id="Meghalaya" />
                <span className="checkmark"></span>
              </label>

              <label htmlFor="Mizoram">
                Mizoram
                <input type="checkbox" id="Mizoram" />
                <span className="checkmark"></span>
              </label>

              <label htmlFor="Nagaland">
                Nagaland
                <input type="checkbox" id="Nagaland" />
                <span className="checkmark"></span>
              </label>

              <label htmlFor="Odisha">
                Odisha
                <input type="checkbox" id="Odisha" />
                <span className="checkmark"></span>
              </label>

              <label htmlFor="Puducherry">
                Puducherry
                <input type="checkbox" id="Puducherry" />
                <span className="checkmark"></span>
              </label>

              <label htmlFor="Punjab">
                Punjab
                <input type="checkbox" id="Punjab" />
                <span className="checkmark"></span>
              </label>

              <label htmlFor="Rajasthan">
                Rajasthan
                <input type="checkbox" id="Rajasthan" />
                <span className="checkmark"></span>
              </label>

              <label htmlFor="Sikkim">
                Sikkim
                <input type="checkbox" id="Sikkim" />
                <span className="checkmark"></span>
              </label>

              <label htmlFor="tn">
                Tamil Nadu
                <input type="checkbox" id="tn" />
                <span className="checkmark"></span>
              </label>

              <label htmlFor="Telangana">
                Telangana
                <input type="checkbox" id="Telangana" />
                <span className="checkmark"></span>
              </label>

              <label htmlFor="Tripura">
                Tripura
                <input type="checkbox" id="Tripura" />
                <span className="checkmark"></span>
              </label>

              <label htmlFor="up">
                Uttar Pradesh
                <input type="checkbox" id="up" />
                <span className="checkmark"></span>
              </label>

              <label htmlFor="uk">
                Uttarakhand
                <input type="checkbox" id="uk" />
                <span className="checkmark"></span>
              </label>

              <label htmlFor="wb">
                West Bengal
                <input type="checkbox" id="wb" />
                <span className="checkmark"></span>
              </label>
            </div>
          </div>

          <div className="tenders-filters-block">
            <h6>City</h6>
            <input
              type="text"
              name="tags"
              data-role="tagsinput"
              placeholder="Enter City"
            />
          </div>

          <div className="tenders-filters-block">
            <h6>Agency</h6>
            <input
              type="text"
              name="tags"
              data-role="tagsinput"
              placeholder="Enter Agency"
            />
          </div>

          <div className="tenders-filters-block">
            <h6>Tender ID</h6>
            <input type="text" placeholder="Enter Tender ID" />
          </div>

          <div className="tenders-filters-block">
            <h6>Due Date</h6>
            <DateRangePicker placeholder="Select Date..." />
          </div>
          <div className="tenders-filters-block">
            <h6>Tender Value</h6>
            <input type="text" placeholder="Enter Tender Value" />
          </div>
          <div className="tenders-filters-block">
            <h6>Department Type</h6>
            <input
              type="text"
              name="tags"
              data-role="tagsinput"
              placeholder="Enter Department Type"
            />
          </div>
          <div className="tenders-filters-block">
            <h6>Tender Type</h6>
            <input
              type="text"
              name="tags"
              data-role="tagsinput"
              placeholder="Enter Tender Type"
            />
          </div>

          <div className="filters-submit">
            <input type="submit" value="Submit"></input>
          </div>
        </div>
      </div>

      <div className="mobile-filters">
        <Button variant="primary" onClick={handleShow}>
          Filters
        </Button>
        <Offcanvas show={show} onHide={handleClose} {...props}>
          <Offcanvas.Header closeButton>
            <Offcanvas.Title>Filters By:</Offcanvas.Title>
          </Offcanvas.Header>
          <Offcanvas.Body>
            <div className="tenders-filters-block">
              <h6>T18 Ref No</h6>
              <input type="text" placeholder="Enter T18 Ref No..." />
            </div>

            <div className="tenders-filters-block">
              <h6>Keyword</h6>
              <input
                type="text"
                name="tags"
                data-role="tagsinput"
                placeholder="Enter Keyword"
              />
            </div>
            <div className="tenders-filters-block">
              <h6>State</h6>
              <div className="filters-checkbox">
                <label htmlFor="andman">
                  Andaman and Nicobar Islands
                  <input type="checkbox" id="andman" />
                  <span className="checkmark"></span>
                </label>

                <label htmlFor="andhra">
                  Andhra Pradesh
                  <input type="checkbox" id="andhra" />
                  <span className="checkmark"></span>
                </label>

                <label htmlFor="Arunachal">
                  Arunachal Pradesh
                  <input type="checkbox" id="Arunachal" />
                  <span className="checkmark"></span>
                </label>

                <label htmlFor="Assam">
                  Assam
                  <input type="checkbox" id="Assam" />
                  <span className="checkmark"></span>
                </label>

                <label htmlFor="Bihar">
                  Bihar
                  <input type="checkbox" id="Bihar" />
                  <span className="checkmark"></span>
                </label>

                <label htmlFor="Chandigarh">
                  Chandigarh
                  <input type="checkbox" id="Chandigarh" />
                  <span className="checkmark"></span>
                </label>

                <label htmlFor="Chhattisgarh">
                  Chhattisgarh
                  <input type="checkbox" id="Chhattisgarh" />
                  <span className="checkmark"></span>
                </label>

                <label htmlFor="Dadra">
                  Dadra and Nagar Haveli
                  <input type="checkbox" id="Dadra" />
                  <span className="checkmark"></span>
                </label>

                <label htmlFor="Daman">
                  Daman And Diu
                  <input type="checkbox" id="Daman" />
                  <span className="checkmark"></span>
                </label>

                <label htmlFor="Delhi">
                  Delhi
                  <input type="checkbox" id="Delhi" />
                  <span className="checkmark"></span>
                </label>

                <label htmlFor="Goa">
                  Goa
                  <input type="checkbox" id="Goa" />
                  <span className="checkmark"></span>
                </label>

                <label htmlFor="Gujarat">
                  Gujarat
                  <input type="checkbox" id="Gujarat" />
                  <span className="checkmark"></span>
                </label>

                <label htmlFor="Haryana">
                  Haryana
                  <input type="checkbox" id="Haryana" />
                  <span className="checkmark"></span>
                </label>

                <label htmlFor="Himachal">
                  Himachal Pradesh
                  <input type="checkbox" id="Himachal" />
                  <span className="checkmark"></span>
                </label>

                <label htmlFor="Jammu">
                  Jammu & Kashmir
                  <input type="checkbox" id="Jammu" />
                  <span className="checkmark"></span>
                </label>

                <label htmlFor="Jharkhand">
                  Jharkhand
                  <input type="checkbox" id="Jharkhand" />
                  <span className="checkmark"></span>
                </label>

                <label htmlFor="Karnataka">
                  Karnataka
                  <input type="checkbox" id="Karnataka" />
                  <span className="checkmark"></span>
                </label>

                <label htmlFor="Kerala">
                  Kerala
                  <input type="checkbox" id="Kerala" />
                  <span className="checkmark"></span>
                </label>

                <label htmlFor="Ladakh">
                  Ladakh
                  <input type="checkbox" id="Ladakh" />
                  <span className="checkmark"></span>
                </label>

                <label htmlFor="Lakshadweep">
                  Lakshadweep
                  <input type="checkbox" id="Lakshadweep" />
                  <span className="checkmark"></span>
                </label>

                <label htmlFor="mp">
                  Madhya Pradesh
                  <input type="checkbox" id="mp" />
                  <span className="checkmark"></span>
                </label>

                <label htmlFor="Maharashtra">
                  Maharashtra
                  <input type="checkbox" id="Maharashtra" />
                  <span className="checkmark"></span>
                </label>

                <label htmlFor="Manipur">
                  Manipur
                  <input type="checkbox" id="Manipur" />
                  <span className="checkmark"></span>
                </label>

                <label htmlFor="Meghalaya">
                  Meghalaya
                  <input type="checkbox" id="Meghalaya" />
                  <span className="checkmark"></span>
                </label>

                <label htmlFor="Mizoram">
                  Mizoram
                  <input type="checkbox" id="Mizoram" />
                  <span className="checkmark"></span>
                </label>

                <label htmlFor="Nagaland">
                  Nagaland
                  <input type="checkbox" id="Nagaland" />
                  <span className="checkmark"></span>
                </label>

                <label htmlFor="Odisha">
                  Odisha
                  <input type="checkbox" id="Odisha" />
                  <span className="checkmark"></span>
                </label>

                <label htmlFor="Puducherry">
                  Puducherry
                  <input type="checkbox" id="Puducherry" />
                  <span className="checkmark"></span>
                </label>

                <label htmlFor="Punjab">
                  Punjab
                  <input type="checkbox" id="Punjab" />
                  <span className="checkmark"></span>
                </label>

                <label htmlFor="Rajasthan">
                  Rajasthan
                  <input type="checkbox" id="Rajasthan" />
                  <span className="checkmark"></span>
                </label>

                <label htmlFor="Sikkim">
                  Sikkim
                  <input type="checkbox" id="Sikkim" />
                  <span className="checkmark"></span>
                </label>

                <label htmlFor="tn">
                  Tamil Nadu
                  <input type="checkbox" id="tn" />
                  <span className="checkmark"></span>
                </label>

                <label htmlFor="Telangana">
                  Telangana
                  <input type="checkbox" id="Telangana" />
                  <span className="checkmark"></span>
                </label>

                <label htmlFor="Tripura">
                  Tripura
                  <input type="checkbox" id="Tripura" />
                  <span className="checkmark"></span>
                </label>

                <label htmlFor="up">
                  Uttar Pradesh
                  <input type="checkbox" id="up" />
                  <span className="checkmark"></span>
                </label>

                <label htmlFor="uk">
                  Uttarakhand
                  <input type="checkbox" id="uk" />
                  <span className="checkmark"></span>
                </label>

                <label htmlFor="wb">
                  West Bengal
                  <input type="checkbox" id="wb" />
                  <span className="checkmark"></span>
                </label>
              </div>
            </div>

            <div className="tenders-filters-block">
              <h6>City</h6>
              <input
                type="text"
                name="tags"
                data-role="tagsinput"
                placeholder="Enter City"
              />
            </div>

            <div className="tenders-filters-block">
              <h6>Agency</h6>
              <input
                type="text"
                name="tags"
                data-role="tagsinput"
                placeholder="Enter Agency"
              />
            </div>

            <div className="tenders-filters-block">
              <h6>Tender ID</h6>
              <input type="text" placeholder="Enter Tender ID" />
            </div>

            <div className="tenders-filters-block">
              <h6>Due Date</h6>
              <DateRangePicker placeholder="Select Date..." />
            </div>
            <div className="tenders-filters-block">
              <h6>Tender Value</h6>
              <input type="text" placeholder="Enter Tender Value" />
            </div>
            <div className="tenders-filters-block">
              <h6>Department Type</h6>
              <input
                type="text"
                name="tags"
                data-role="tagsinput"
                placeholder="Enter Department Type"
              />
            </div>
            <div className="tenders-filters-block">
              <h6>Tender Type</h6>
              <input
                type="text"
                name="tags"
                data-role="tagsinput"
                placeholder="Enter Tender Type"
              />
            </div>

            <div className="filters-submit">
              <input type="submit" value="Submit"></input>
            </div>
          </Offcanvas.Body>
        </Offcanvas>
      </div>
    </>
  );
};

export default LiveTendersFilters;
