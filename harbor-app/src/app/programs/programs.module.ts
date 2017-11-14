import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { SharedModule } from '../shared/shared.module';
import { FormsModule } from '@angular/forms';
import { SubmitService } from './submit.service';
import { AngularFirestoreModule } from 'angularfire2/firestore';
import { Build003Component } from './build003.component';
@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    SharedModule,
    AngularFirestoreModule.enablePersistence(),
  ],
  declarations: [
    Build003Component,
  ],
  providers: [SubmitService]
})
export class ProgramsModule {}
