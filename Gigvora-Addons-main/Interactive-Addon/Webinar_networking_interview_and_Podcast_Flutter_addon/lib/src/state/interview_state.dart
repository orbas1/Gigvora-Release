import 'package:flutter/foundation.dart';

import '../models/interview.dart';
import '../models/interview_score.dart';
import '../models/interview_slot.dart';
import '../services/interview_service.dart';
import 'live_states.dart';

class InterviewState extends ChangeNotifier {
  InterviewState(this.service);

  final InterviewService service;

  final ViewState<List<Interview>> interviews = ViewState(data: []);
  final ViewState<Interview> selected = ViewState();
  InterviewScore? latestScore;

  Future<void> loadInterviews() async {
    try {
      interviews.setLoading();
      final response = await service.fetchInterviews();
      if (response.data.isEmpty) {
        interviews.setEmpty();
      } else {
        interviews.setData(response.data);
      }
    } catch (error) {
      interviews.setError(error.toString());
    }
  }

  Future<void> selectInterview(int id) async {
    try {
      selected.setLoading();
      final detail = await service.fetchDetail(id);
      selected.setData(detail);
    } catch (error) {
      selected.setError(error.toString());
    }
  }

  Future<void> addSlot(int interviewId, InterviewSlotPayload payload) async {
    await service.addSlot(interviewId, payload);
    await selectInterview(interviewId);
  }

  Future<void> submitScore(int interviewId, int slotId, InterviewScorePayload payload) async {
    latestScore = await service.submitScore(interviewId, slotId, payload);
    notifyListeners();
  }
}
