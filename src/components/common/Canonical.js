"use client";

import { usePathname } from "next/navigation";

const Canonical = () => {
  const pathName = usePathname();
  return (
    <link rel="canonical" href={process.env.NEXT_PUBLIC_BASE_URL + pathName} />
  );
};

export default Canonical;
