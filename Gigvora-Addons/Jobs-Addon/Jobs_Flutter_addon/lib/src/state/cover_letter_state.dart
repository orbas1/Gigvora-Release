import 'package:flutter/foundation.dart';

import '../models/cover_letter.dart';
import '../services/cover_letter_service.dart';

class CoverLetterState extends ChangeNotifier {
  CoverLetterState(this.service);

  final CoverLetterService service;
  List<CoverLetter> letters = <CoverLetter>[];
  CoverLetter? editing;

  Future<void> load() async {
    letters = await service.fetchLetters();
    notifyListeners();
  }

  Future<void> save(CoverLetter letter) async {
    await service.saveLetter(letter);
    await load();
  }
}
