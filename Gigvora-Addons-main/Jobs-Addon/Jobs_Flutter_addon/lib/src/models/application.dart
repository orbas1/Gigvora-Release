import 'ats.dart';
import 'candidate.dart';
import 'cover_letter.dart';
import 'cv.dart';
import 'job.dart';

class JobApplication {
  const JobApplication({
    required this.id,
    required this.job,
    required this.candidate,
    required this.status,
    this.cv,
    this.coverLetter,
  });

  final int id;
  final Job job;
  final CandidateProfile candidate;
  final AtsStage status;
  final CvDocument? cv;
  final CoverLetter? coverLetter;

  factory JobApplication.fromJson(Map<String, dynamic> json) => JobApplication(
        id: json['id'] as int,
        job: Job.fromJson(json['job'] as Map<String, dynamic>),
        candidate:
            CandidateProfile.fromJson(json['candidate'] as Map<String, dynamic>),
        status: AtsStage.fromJson(json['status'] as Map<String, dynamic>),
        cv: json['cv'] != null
            ? CvDocument.fromJson(json['cv'] as Map<String, dynamic>)
            : null,
        coverLetter: json['cover_letter'] != null
            ? CoverLetter.fromJson(json['cover_letter'] as Map<String, dynamic>)
            : null,
      );

  Map<String, dynamic> toJson() => {
        'id': id,
        'job': job.toJson(),
        'candidate': candidate.toJson(),
        'status': status.toJson(),
        'cv': cv?.toJson(),
        'cover_letter': coverLetter?.toJson(),
      };
}
