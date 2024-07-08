"use client";
import React, { useEffect } from "react";
import ArchiveTendersList from "./ArchiveTendersList";
import ArchiveTendersFilters from "./ArchiveTendersFilters";

const ArchiveTendersFlex = () => {
  return (
    <>
      <div className="tenders-details-main">
        <div className="container-main">
          <div className="tenders-page-title">
            <h2>Latest Tenders in Archive</h2>
          </div>
          <ArchiveTendersList />
        </div>
      </div>
    </>
  );
};

export default ArchiveTendersFlex;
