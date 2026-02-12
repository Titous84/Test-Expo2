import LinearProgress, { LinearProgressProps } from '@mui/material/LinearProgress';
import Typography from '@mui/material/Typography';
import Box from '@mui/material/Box';

/**
 * @author Christopher Boisvert
 *  Fonction qui permet de générer un ligne de progression linéaire avec une étiquette.
 * @param props Prend comme paramètres un LinearProgressProps et une valeur d'entrée.
 * @returns Retourne un objet React contenant la ligne de progression.
 */
function LinearProgressWithLabel(props: LinearProgressProps & { value: number }): JSX.Element {
  return (
    <Box sx={{ display: 'flex', alignItems: 'center' }}>
      <Box sx={{ width: '100%', mr: 1 }}>
        <LinearProgress variant="determinate" {...props} />
      </Box>
      <Box sx={{ minWidth: 35 }}>
        <Typography variant="body2" color="text.secondary">{`${Math.round(
          props.value,
        )}%`}</Typography>
      </Box>
    </Box>
  );
}

/**
 * @author Christopher Boisvert
 *  Fonction qui permet de générer un ligne de progression linéaire avec une étiquette de la valeur.
 * @param props Prend comme paramètres un LinearProgressProps et une valeur d'entrée.
 * @returns Retourne un objet React contenant la ligne de progression.
 */
export default function LinearWithValueLabel(props: LinearProgressProps & { value: number }): JSX.Element {
  return (
    <Box sx={{ width: '100%' }}>
      <LinearProgressWithLabel value={props.value} />
    </Box>
  );
}