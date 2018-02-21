import { Injectable } from '@angular/core';
import { AngularFirestore, AngularFirestoreCollection, AngularFirestoreDocument } from 'angularfire2/firestore';
import { Observable } from 'rxjs/Observable';
import 'rxjs/add/operator/map';
// interface Submit {
//   content: string;
//   hearts ? : number;
//   id ? : any;
//   time ? : number;
// }
@Injectable()
export class SubmitService {
  submitsCollection: AngularFirestoreCollection < any > ;
  submitDocument: AngularFirestoreDocument < Node >
    constructor(private afs: AngularFirestore) {
      this.submitsCollection = this.afs.collection('build004-submits', ref => ref.orderBy('time', 'desc'))
      // this.submitDocument = this.afs.doc('submits/mtp1Ll6caN4dVrhg8fWD');
    }
  getData(): Observable < any[] > {
    return this.submitsCollection.valueChanges();
  }
  getSnapshot() {
    // ['added', 'modified', 'removed']
    return this.submitsCollection.snapshotChanges().map(actions => {
      return actions.map(a => {
        return { id: a.payload.doc.id, ...a.payload.doc.data() }
      })
    })
  }
  getSubmit(id) {
    return this.afs.doc < any > ('build004-submits/' + id);
  }
  create(submit_name, submit_email, submit_dev_or_design, submit_av_lang, submit_career, submit_age, submit_phone, submit_message) {
    const submit: any = {
      submit_name: submit_name,
      submit_email: submit_email,
      submit_dev_or_design: submit_dev_or_design,
      submit_av_lang: submit_av_lang,
      submit_career: submit_career,
      submit_age: submit_age,
      submit_phone: submit_phone,
      submit_message: submit_message,
      time: new Date().getTime()
    }
    return this.submitsCollection.add(submit);
  }
  updateSubmit(id, data) {
    return this.getSubmit(id).update(data)
  }
  deleteSubmit(id) {
    return this.getSubmit(id).delete()
  }
}
