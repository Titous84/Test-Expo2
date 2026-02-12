/**
 * @author Tristan Lafontaine
 */
import * as React from "react";
import Typography from "@mui/material/Typography";
import { Fade, IconButton, Paper, Popper } from "@mui/material";
import HelpOutlineIcon from "@mui/icons-material/HelpOutline";
import styles from "./../../pages/ParticipantRegistration/ParticipantRegistrationPage.module.css";

interface PopoverProps {
  text: string;
  color: "black" | "white";
}

export default function BasicPopover(props: PopoverProps) {
  const [anchorEl, setAnchorEl] = React.useState<HTMLButtonElement | null>(
    null
  );

  const open = Boolean(anchorEl);
  const id = open ? "simple-popover" : undefined;

  const handleClick = (event: React.MouseEvent<HTMLButtonElement>) => {
    if (open) return handleClose();
    setAnchorEl(event.currentTarget);
  };

  const handleClose = () => {
    setAnchorEl(null);
  };

  return (
    <div>
      <IconButton onClick={handleClick}>
        <HelpOutlineIcon
          className={props.color === "black" ? "" : styles.helpWhite}
          aria-label="Aide"
        />
      </IconButton>
      <Popper
        id={id}
        open={open}
        anchorEl={anchorEl}
        transition
      >
        {({ TransitionProps }) => (
          <Fade {...TransitionProps} timeout={350}>
            <Paper>
              <Typography sx={{ p: 2 }}>{props.text}</Typography>
            </Paper>
          </Fade>
        )}
      </Popper>
    </div>
  );
}
