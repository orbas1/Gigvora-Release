import 'package:flutter/foundation.dart';

import '../data/jobs_repository.dart';
import '../models/models.dart';

class JobsNotifier extends ChangeNotifier {
  JobsNotifier(this._repository);

  final JobsRepository _repository;
  List<Job> jobs = <Job>[];
  Job? selected;
  bool loading = false;
  String? error;

  Future<void> loadJobs({String? search}) async {
    loading = true;
    error = null;
    notifyListeners();
    try {
      jobs = await _repository.fetchJobs(query: search);
    } catch (e) {
      error = e.toString();
    } finally {
      loading = false;
      notifyListeners();
    }
  }

  Future<void> loadJob(int id) async {
    loading = true;
    error = null;
    notifyListeners();
    try {
      selected = await _repository.fetchJob(id);
    } catch (e) {
      error = e.toString();
    } finally {
      loading = false;
      notifyListeners();
    }
  }
}
