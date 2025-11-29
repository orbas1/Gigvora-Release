import 'package:flutter/foundation.dart';

import '../models/cv.dart';
import '../services/cv_service.dart';

class CvState extends ChangeNotifier {
  CvState(this.service);

  final CvService service;

  List<CvDocument> cvs = <CvDocument>[];
  CvDocument? editing;
  bool loading = false;

  Future<void> load() async {
    loading = true;
    notifyListeners();
    cvs = await service.fetchCvs();
    loading = false;
    notifyListeners();
  }

  Future<void> edit(int id) async {
    editing = await service.fetchCv(id);
    notifyListeners();
  }

  Future<void> save(CvDocument cv) async {
    await service.saveCv(cv);
    await load();
  }

  Future<void> remove(int id) async {
    await service.deleteCv(id);
    await load();
  }
}
