"use client";
import React, { useEffect } from "react";
import LiveTendersList from "./LiveTendersList";

const TendersFlex = () => {
  return (
    <>
      <div className="tenders-details-main">
        <div className="container-main">
          <div className="tenders-page-title">
            <h2>Online Live Tenders, Public Tender For Free Consultant</h2>
          </div>
          <LiveTendersList />
        </div>
      </div>
    </>
  );
};

export default TendersFlex;
