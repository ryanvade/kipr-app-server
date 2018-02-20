import { NgModule } from '@angular/core';
import { IonicPageModule } from 'ionic-angular';
import { TeamSignInPickerPage } from './team-sign-in-picker';

@NgModule({
  declarations: [
    TeamSignInPickerPage,
  ],
  imports: [
    IonicPageModule.forChild(TeamSignInPickerPage),
  ],
})
export class TeamSignInPickerPageModule {}
