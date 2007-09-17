<?php

$neighbors = Array(
'S03I4' => Array('SDRI4', 'SKCI4', 'SBZI4', 'SAKI4', 'SIAI4'),
'SAFI4' => Array('SCSI4', 'SMUI4', 'SMOI4', 'SWII4', 'SOSI4'),
'SAKI4' => Array('SPKI4', 'SKCI4', 'SGRI4', 'SDRI4', 'SIAI4'),
'SALI4' => Array('SCEI4', 'SOCI4', 'SPEI4', 'SKNI4', 'SBMI4'),
'SBDI4' => Array('SCNI4', 'SMOI4', 'SCSI4', 'SFOI4', 'SAFI4'),
'SBMI4' => Array('SCEI4', 'SALI4', 'SOCI4', 'SPEI4', 'SMNI4'),
'SBZI4' => Array('SDRI4', 'SKCI4', 'SIAI4', 'SINI4', 'SAKI4'),
'SCAI4' => Array('SGLI4', 'SCBI4', 'SWAI4', 'SAUI4', 'SROI4'),
'SCBI4' => Array('SGLI4', 'SCAI4', 'SAUI4', 'SJEI4', 'SPAI4'),
'SCDI4' => Array('SCHI4', 'SLEI4', 'SCEI4', 'SOSI4', 'SALI4'),
'SCEI4' => Array('SALI4', 'SCDI4', 'SBMI4', 'SCHI4', 'SOCI4'),
'SCHI4' => Array('SCDI4', 'SKNI4', 'SINI4', 'SOSI4', 'SALI4'),
'SCSI4' => Array('SAFI4', 'SFOI4', 'SCNI4', 'SMOI4', 'SMUI4'),
'SFAI4' => Array('SMSI4', 'SROI4', 'SFDI4', 'SJEI4', 'SGLI4'),
'SGLI4' => Array('SCAI4', 'SCBI4', 'SROI4', 'SJEI4', 'SAUI4'),
'SJWI4' => Array('SWBI4', 'SAMI4', 'SBOI4', 'SNVI4', 'SCGI4'),
'SKNI4' => Array('SPEI4', 'SCHI4', 'SSUI4', 'SNEI4', 'SALI4'),
'SLEI4' => Array('SLOI4', 'SOSI4', 'SMUI4', 'SCDI4', 'SMOI4'),
'SLMI4' => Array('SIFI4', 'SBKI4', 'SCGI4', 'SJWI4', 'SWBI4'),
'SLOI4' => Array('SLEI4', 'SMOI4', 'SMUI4', 'SOSI4', 'SAFI4'),
'SMNI4' => Array('SBRI4', 'SOCI4', 'SSUI4', 'SPEI4', 'SNEI4'),
'SMOI4' => Array('SLOI4', 'SAFI4', 'SCSI4', 'SMUI4', 'SLEI4'),
'SMUI4' => Array('SOSI4', 'SAFI4', 'SWII4', 'SLEI4', 'SLOI4'),
'SOCI4' => Array('SPEI4', 'SALI4', 'SMNI4', 'SSUI4', 'SKNI4'),
'SOSI4' => Array('SMUI4', 'SLEI4', 'SINI4', 'SWII4', 'SAFI4'),
'SPEI4' => Array('SSUI4', 'SKNI4', 'SOCI4', 'SNEI4', 'SALI4'),
'SROI4' => Array('SFAI4', 'SMSI4', 'SGLI4', 'SCAI4', 'SWAI4'),
'SSUI4' => Array('SPEI4', 'SNEI4', 'SMNI4', 'SOCI4', 'SKNI4'),
'SWAI4' => Array('SCAI4', 'SGLI4', 'SROI4', 'SCBI4', 'SAUI4'),
'SBZI4' => Array('SDRI4', 'SKCI4', 'SIAI4', 'SJCI4', 'SINI4'),
'SDRI4' => Array('SBZI4', 'SKCI4', 'SIAI4', 'SAKI4', 'SJCI4'),
'SINI4' => Array('SBZI4', 'SDRI4', 'SKCI4', 'SJCI4', 'SIAI4'),
'SKCI4' => Array('SDRI4', 'SBZI4', 'SIAI4', 'SJCI4', 'SAKI4'),
'SWII4' => Array('SAEI4', 'SMUI4', 'SJCI4', 'SAFI4', 'SIAI4'),
'SJEI4' => Array('SPAI4', 'SCBI4', 'SPYI4', 'SFAI4', 'SOGI4'),
'SAUI4' => Array('SCBI4', 'SATI4', 'SCAI4', 'SADI4', 'SGLI4'),
'SCNI4' => Array('SBDI4', 'SFOI4', 'SCSI4', 'SATI4', 'SADI4'),
'SMLI4' => Array('SAGI4', 'SHUI4', 'SMSI4', 'SROI4', 'SLUI4'),
'SAGI4' => Array('SLUI4', 'SHUI4', 'SMLI4', 'SEGI4', 'SFDI4'),
'SBKI4' => Array('SCGI4', 'SLMI4', 'SEGI4', 'SWBI4', 'SIFI4'),
'SCGI4' => Array('SBKI4', 'SEGI4', 'SWBI4', 'SLMI4', 'SLUI4'),
'SEGI4' => Array('SCGI4', 'SWBI4', 'SLUI4', 'SFDI4', 'SHUI4'),
'SFDI4' => Array('SMSI4', 'SHUI4', 'SFAI4', 'SEGI4', 'SWBI4'),
'SHUI4' => Array('SFDI4', 'SLUI4', 'SMSI4', 'SEGI4', 'SAGI4'),
'SLUI4' => Array('SAGI4', 'SHUI4', 'SEGI4', 'SCGI4', 'SFDI4'),
'SMSI4' => Array('SFAI4', 'SFDI4', 'SHUI4', 'SROI4', 'SEGI4'),
'SWBI4' => Array('SEGI4', 'SJWI4', 'SCGI4', 'SFDI4', 'SBKI4'),
'SAMI4' => Array('SBOI4', 'SNVI4', 'SJWI4', 'SMDI4', 'SPKI4'),
'SIFI4' => Array('SLMI4', 'SUNI4', 'SJWI4', 'SBKI4', 'SCGI4'),
'SUNI4' => Array('SMAI4', 'SCOI4', 'SIFI4', 'SNVI4', 'SNEI4'),
'SBRI4' => Array('SMNI4', 'STQI4', 'SSUI4', 'SOCI4', 'SMAI4'),
'SMNI4' => Array('SBRI4', 'SOCI4', 'SSUI4', 'STQI4', 'SPEI4'),
'STQI4' => Array('SBRI4', 'SMAI4', 'SMNI4', 'SSUI4', 'SNEI4'),
'SCOI4' => Array('SNVI4', 'SBXI4', 'SAMI4', 'SUNI4', 'SMAI4'),
'SMAI4' => Array('SUNI4', 'STQI4', 'SBXI4', 'SNEI4', 'SCOI4'),
'SNEI4' => Array('SBXI4', 'SSUI4', 'SPEI4', 'SMAI4', 'SKNI4'),
'SNVI4' => Array('SCOI4', 'SAMI4', 'SBOI4', 'SAKI4', 'SBXI4'),
'SUNI4' => Array('SMAI4', 'SCOI4', 'SIFI4', 'SBXI4', 'SNVI4'),
'SBXI4' => Array('SNEI4', 'SCOI4', 'SMAI4', 'SNVI4', 'SSUI4'),
'SADI4' => Array('SATI4', 'SFOI4', 'SSTI4', 'SPAI4', 'SCBI4'),
'SATI4' => Array('SADI4', 'SFOI4', 'SAUI4', 'SCBI4', 'SSTI4'),
'SFOI4' => Array('SADI4', 'SATI4', 'SCSI4', 'SSTI4', 'SCNI4'),
'SPAI4' => Array('SSTI4', 'SADI4', 'SJEI4', 'SPYI4', 'SCBI4'),
'SWII4' => Array('SAEI4', 'SMUI4', 'SJCI4', 'SSTI4', 'SAFI4'),
'SSTI4' => Array('SPAI4', 'SADI4', 'SAEI4', 'SFOI4', 'SWII4'),
'SDCI4' => Array('SGRI4', 'SIAI4', 'SJCI4', 'SPKI4', 'SKCI4'),
'SGRI4' => Array('SDCI4', 'SPKI4', 'SIAI4', 'SJCI4', 'SAKI4'),
'SIAI4' => Array('SJCI4', 'SDCI4', 'SKCI4', 'SGRI4', 'SBZI4'),
'SJCI4' => Array('SIAI4', 'SDCI4', 'SGRI4', 'SKCI4', 'SBZI4'),
'SPKI4' => Array('SAKI4', 'SGRI4', 'SMDI4', 'SDCI4', 'SIAI4'),
'SAEI4' => Array('SDCI4', 'SPYI4', 'SGRI4', 'SWGI4', 'SJCI4'),
'SBOI4' => Array('SMDI4', 'SAMI4', 'SWGI4', 'SPKI4', 'SOGI4'),
'SMDI4' => Array('SWGI4', 'SPKI4', 'SBOI4', 'SGRI4', 'SAKI4'),
'SOGI4' => Array('SPYI4', 'SWGI4', 'SBOI4', 'SMDI4', 'SJEI4'),
'SPYI4' => Array('SWGI4', 'SOGI4', 'SAEI4', 'SMDI4', 'SGRI4'),
'SWGI4' => Array('SMDI4', 'SPYI4', 'SPKI4', 'SGRI4', 'SOGI4'),
);

?>
