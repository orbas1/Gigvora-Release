import '../api/freelance_api_client.dart';
import '../models/bid.dart';
import '../models/dispute.dart';
import '../models/dispute_stage.dart';
import '../models/escrow.dart';
import '../models/escrow_action.dart';
import '../models/gig.dart';
import '../models/pagination.dart';
import '../models/project.dart';
import '../models/project_board.dart';
import '../models/gig_management.dart';
import '../models/recommendations.dart';
import '../models/search_result.dart';
import '../models/tag.dart';
import '../models/profile_portfolio.dart';
import '../models/education_entry.dart';
import '../models/certification.dart';
import '../models/profile_review.dart';

class FreelanceRepository {
  FreelanceRepository({required this.apiClient});

  final FreelanceApiClient apiClient;

  Future<PagedResult<Gig>> fetchGigs({Map<String, dynamic> filters = const {}}) {
    return apiClient.fetchGigs(params: filters);
  }

  Future<Gig> fetchGigDetails(int id) => apiClient.fetchGigDetails(id);

  Future<List<FreelanceTag>> fetchTags({String? type}) => apiClient.fetchTags(type: type);

  Future<PagedResult<Project>> fetchProjects({Map<String, dynamic> filters = const {}}) {
    return apiClient.fetchProjects(params: filters);
  }

  Future<Project> fetchProjectDetails(String slug) => apiClient.fetchProjectDetails(slug);

  Future<List<Dispute>> fetchDisputes({int perPage = 20}) => apiClient.fetchDisputes(perPage: perPage);

  Future<List<Escrow>> fetchEscrows() => apiClient.fetchEscrows();

  Future<ProjectBoard> fetchProjectBoard(String slug) => apiClient.fetchProjectBoard(slug);
  Future<FreelanceSearchResult> searchFreelance({required String query, int page = 1, int perPage = 20}) {
    return apiClient.searchFreelance(query: query, page: page, perPage: perPage);
  }

  Future<FreelanceRecommendations> fetchRecommendations({int limit = 10}) {
    return apiClient.fetchRecommendations(limit: limit);
  }
  Future<GigManagement> fetchGigManagement(int id) => apiClient.fetchGigManagement(id);
  Future<List<DisputeStage>> fetchDisputeStages(int disputeId) => apiClient.fetchDisputeStages(disputeId);
  Future<void> advanceDispute({required int disputeId, required String stage, String? notes, String? decision}) {
    return apiClient.advanceDispute(disputeId: disputeId, stage: stage, notes: notes, decision: decision);
  }
  Future<List<EscrowAction>> fetchEscrowActions() => apiClient.fetchEscrowActions();
  Future<void> partialRelease({required int escrowId, required double amount, required String releasedBy, String? notes}) {
    return apiClient.partialRelease(escrowId: escrowId, amount: amount, releasedBy: releasedBy, notes: notes);
  }

  Future<void> updateProfileTags({required List<String> tags, String type = 'freelancer'}) {
    return apiClient.updateProfileTags(tags: tags, type: type);
  }

  Future<void> updateGigTags({required int gigId, required List<String> tags}) {
    return apiClient.updateGigTags(gigId: gigId, tags: tags);
  }

  Future<void> recordEscrowDecision({required int escrowId, required String decision, required String admin, String? notes}) {
    return apiClient.recordEscrowDecision(escrowId: escrowId, decision: decision, admin: admin, notes: notes);
  }

  Future<Dispute> openDispute({
    required String subject,
    required String referenceType,
    required int referenceId,
    String? message,
  }) {
    return apiClient.openDispute(
      subject: subject,
      referenceType: referenceType,
      referenceId: referenceId,
      message: message,
    );
  }

  Future<Bid> placeBid({
    required String projectSlug,
    required double amount,
    required String currency,
    String? coverLetter,
  }) {
    return apiClient.placeBid(
      projectSlug: projectSlug,
      amount: amount,
      currency: currency,
      coverLetter: coverLetter,
    );
  }

  Future<void> toggleFavourite({required int id, required String type}) {
    return apiClient.toggleFavourite(id: id, type: type);
  }

  Future<List<ProfilePortfolio>> fetchPortfolios({int? userId}) {
    return apiClient.fetchPortfolios(userId: userId);
  }

  Future<ProfilePortfolio> addPortfolio(Map<String, dynamic> payload) {
    return apiClient.addPortfolio(payload);
  }

  Future<ProfilePortfolio> updatePortfolio(int id, Map<String, dynamic> payload) {
    return apiClient.updatePortfolio(id, payload);
  }

  Future<void> deletePortfolio(int id) {
    return apiClient.deletePortfolio(id);
  }

  Future<List<EducationEntry>> fetchEducations({int? userId}) {
    return apiClient.fetchEducations(userId: userId);
  }

  Future<EducationEntry> addEducation(Map<String, dynamic> payload) {
    return apiClient.addEducation(payload);
  }

  Future<EducationEntry> updateEducation(int id, Map<String, dynamic> payload) {
    return apiClient.updateEducation(id, payload);
  }

  Future<void> deleteEducation(int id) {
    return apiClient.deleteEducation(id);
  }

  Future<List<Certification>> fetchCertifications({int? userId}) {
    return apiClient.fetchCertifications(userId: userId);
  }

  Future<Certification> addCertification(Map<String, dynamic> payload) {
    return apiClient.addCertification(payload);
  }

  Future<Certification> updateCertification(int id, Map<String, dynamic> payload) {
    return apiClient.updateCertification(id, payload);
  }

  Future<void> deleteCertification(int id) {
    return apiClient.deleteCertification(id);
  }

  Future<Map<String, dynamic>> fetchProfileReviews(int userId) {
    return apiClient.fetchProfileReviews(userId);
  }

  Future<ProfileReview> submitProfileReview({
    required int userId,
    required double rating,
    String? comment,
    String? reference,
  }) {
    return apiClient.submitProfileReview(
      userId: userId,
      rating: rating,
      comment: comment,
      reference: reference,
    );
  }
}
