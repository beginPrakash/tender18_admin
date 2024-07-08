"use client";
import React, { useEffect, useState } from "react";
import axios from "axios";

const Profile = () => {
  const [data, setData] = useState([]);
  const [isLoggedIn, setIsLoggedIn] = useState(false);

  useEffect(() => {
    const validateLogin = async () => {
      const token = localStorage.getItem("token");
      const user_id = localStorage.getItem("user_id");
      console.log({ token }, { user_id });
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
              endpoint: "getProfileData",
            };
            const response1 = await axios.post(
              `${process.env.NEXT_PUBLIC_API_BASEURL}` + "profile.php",
              user1
            );
            setData(response1.data.data);
          } else {
            // console.log("guest");
            setIsLoggedIn(false);
          }
        } catch (error) {
          console.error(error);
        } finally {
          // setLoading(false);
        }
      } else {
        setIsLoggedIn(false);
        // console.log("guest");
        if (typeof window !== "undefined") {
          window.location.href = "/sign-in";
        }
      }

    };
    validateLogin();
  }, []);

  return (
    <>
      <div className="login-form-main">
        <div className="container-main">
          <div className="regsiter-form-widths">
            <div className="register-form-title">
              <h2>Profile</h2>
            </div>

            <div className="profile-table tender-details-table">
              <table>
                <tbody>
                  <tr>
                    <td>Username</td>
                    <td>{data.users_name}</td>
                  </tr>
                  <tr>
                    <td>Email</td>
                    <td>{data.users_email}</td>
                  </tr>
                  <tr>
                    <td>Company Name</td>
                    <td>{data.company_name}</td>
                  </tr>
                  <tr>
                    <td>Customer Name</td>
                    <td>{data.customer_name}</td>
                  </tr>
                  <tr>
                    <td>Alternate Email</td>
                    <td>{data.alt_email}</td>
                  </tr>
                  <tr>
                    <td>Mobile Number</td>
                    <td>{data.mobile_number}</td>
                  </tr>
                  <tr>
                    <td>Alternate Mobile</td>
                    <td>{data.alt_mobile}</td>
                  </tr>
                  <tr>
                    <td>Whatsapp Alert Number</td>
                    <td>{data.whatsapp_alert_no}</td>
                  </tr>
                  <tr>
                    <td>Address</td>
                    <td>{data.address}</td>
                  </tr>
                  <tr>
                    <td>State</td>
                    <td>{data.state}</td>
                  </tr>
                  <tr>
                    <td>Start Date</td>
                    <td>{data.start_date}</td>
                  </tr>
                  <tr>
                    <td>Duration</td>
                    <td>{data.duration}</td>
                  </tr>
                  <tr>
                    <td>Expired Date</td>
                    <td>{data.expired_date}</td>
                  </tr>
                  <tr>
                    <td>Customer Status</td>
                    <td>{data.status}</td>
                  </tr>
                  <tr>
                    <td>Keywords</td>
                    <td>{data.keywords}</td>
                  </tr>
                  <tr>
                    <td>Words</td>
                    <td>{data.words}</td>
                  </tr>
                  <tr>
                    <td>Not Used Keywords</td>
                    <td>{data.not_used_keywords}</td>
                  </tr>
                  <tr>
                    <td>Filter City</td>
                    <td>{data.filter_city}</td>
                  </tr>
                  <tr>
                    <td>Filter Agency</td>
                    <td>{data.filter_agency}</td>
                  </tr>
                  <tr>
                    <td>Filter Department Type</td>
                    <td>{data.filter_department}</td>
                  </tr>
                  <tr>
                    <td>Filter Type</td>
                    <td>{data.filter_type}</td>
                  </tr>
                  {/* <tr>
                                    <td>All Filters</td>
                                    <td>{data.all_filters}</td>
                                </tr> */}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </>
  );
};

export default Profile;
