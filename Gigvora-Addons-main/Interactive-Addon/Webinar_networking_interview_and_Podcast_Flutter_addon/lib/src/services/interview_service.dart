import '../api/wnip_api_client.dart';
import '../models/interview.dart';
import '../models/interview_score.dart';
import '../models/interview_slot.dart';
import '../models/pagination.dart';

class InterviewService {
  final WnipApiClient apiClient;

  InterviewService(this.apiClient);

  Future<PaginatedResponse<Interview>> fetchInterviews({int page = 1}) {
    return apiClient.fetchInterviews(page: page);
  }

  Future<Interview> fetchDetail(int id) {
    return apiClient.fetchInterviewDetails(id);
  }

  Future<InterviewScore> submitScore(int interviewId, int slotId, InterviewScorePayload payload) {
    return apiClient.submitInterviewScore(interviewId, slotId, payload);
  }

  Future<InterviewSlot> addSlot(int interviewId, InterviewSlotPayload payload) {
    return apiClient.addInterviewSlot(interviewId, payload);
  }
}
