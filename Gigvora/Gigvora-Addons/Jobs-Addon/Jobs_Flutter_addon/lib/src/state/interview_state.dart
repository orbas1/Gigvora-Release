import 'package:flutter/foundation.dart';

import '../models/interview.dart';
import '../services/interview_service.dart';

class InterviewState extends ChangeNotifier {
  InterviewState(this.service);

  final InterviewService service;
  List<InterviewInvite> interviews = <InterviewInvite>[];

  Future<void> load() async {
    interviews = await service.fetchInterviews();
    notifyListeners();
  }

  Future<void> schedule(InterviewInvite invite) async {
    await service.scheduleInterview(invite);
    await load();
  }

  Future<void> reschedule(int id, String dateTime) async {
    await service.reschedule(id, dateTime);
    await load();
  }

  Future<void> cancel(int id) async {
    await service.cancel(id);
    await load();
  }
}
