import { NgModule } from '@angular/core';
import { IonicPageModule } from 'ionic-angular';
import { CompetitionInfoPage } from './competitionInfo';

@NgModule({
    declarations: [
      CompetitionInfoPage,
    ],
    imports: [
      IonicPageModule.forChild(CompetitionInfoPage),
    ],
  })
  
  export class CompetitionInfoPageModule {}