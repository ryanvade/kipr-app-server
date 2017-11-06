import { NgModule } from '@angular/core';
import { IonicPageModule } from 'ionic-angular';
import { JudgingPage } from './judging';

@NgModule({
  declarations: [
    JudgingPage,
  ],
  imports: [
    IonicPageModule.forChild(JudgingPage),
  ],
})
export class JudgingPageModule {}
