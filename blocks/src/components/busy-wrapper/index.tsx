import { ReactNode } from "react";

type Props = {
  isBusy: boolean;
  children: ReactNode;
  overlay?: boolean;
};

const BusyWrapper = ({ isBusy, children, overlay = true }: Props) => {
  return (
    <div className="busy-wrapper">
      {children}
      {isBusy && (
        <div className={`busy-overlay ${overlay ? "with-background" : ""}`}>
          <div className="spinner" />
        </div>
      )}
    </div>
  );
};

export default BusyWrapper;
